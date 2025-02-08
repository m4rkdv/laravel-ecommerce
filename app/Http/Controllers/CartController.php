<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\Address;
use App\Models\OrderItem;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Services\MercadoPagoService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Surfsidemedia\Shoppingcart\Facades\Cart;

class CartController extends Controller
{
    public function index()
    {
        $items = Cart::instance('cart')->content();
        return view('cart',compact('items'));
    }
    
    public function add_to_cart(Request $request)
    {
        Cart::instance('cart')->add($request->id,$request->name,$request->quantity,$request->price)->associate('App\Models\Product');
        return redirect()->back();
    }

    public function increase_cart_quantity($rowId)
    {
        $product = Cart::instance('cart')->get($rowId);
        $qty = $product->qty+1;
        Cart::instance('cart')->update($rowId,$qty);
        return redirect()->back();
    }

    public function decrease_cart_quantity($rowId)
    {
        $product = Cart::instance('cart')->get($rowId);
        $qty = $product->qty - 1;
        Cart::instance('cart')->update($rowId,$qty);
        return redirect()->back();
    }

    public function remove_item($rowId)
    {
        Cart::instance('cart')->remove($rowId);
        return redirect()->back();
    }

    public function empty_cart()
    {
        Cart::instance('cart')->destroy();
        Session::forget('coupon');
        Session::forget('discounts');
        return redirect()->back();
    }

    public function apply_coupon_code(Request $request)
    {
        $coupon_code = $request->coupon_code;
        if(isset($coupon_code))
        {
            $coupon = Coupon::where('code',$coupon_code)->where('expiry_date','>=',Carbon::today())
            ->where('cart_value','<=',Cart::instance('cart')->subtotal())->first();
            if(!$coupon)
            {
                return redirect()->back()->with('error','Invalid coupon code.');
            }
            else{
                Session::put('coupon',[
                    'code' => $coupon->code,
                    'type' => $coupon->type,
                    'value' => $coupon->value,
                    'cart_value' => $coupon->cart_value
                ]);
                $this->calculateDiscount();
                return redirect()->back()->with('succes','Coupon has been applied!');
            }
        }else{
            return redirect()->back()->with('error','Invalid coupon code.');
        }     
    }

    public function calculateDiscount()
    {
        $discount = 0;
        if (Session::has('coupon'))
        {
            if(Session::get('coupon')['type']=='fixed')
            {
                $discount = Session::get('coupon')['value'];
            }else{
                $discount = (Cart::instance('cart')->subtotal() * Session::get('coupon')['value'])/100;
            }
            $subTotalAfterDiscount = Cart::instance('cart')->subtotal() - $discount;
           // $taxAfterDiscount = ($subTotalAfterDiscount * config('cart.tax'))/100;
            $totalAfterDiscount = $subTotalAfterDiscount ;

            Session::put('discounts',[
                'discount' => number_format(floatval($discount),2,'.',''),
                'subtotal' => number_format(floatval($subTotalAfterDiscount),2,'.',','),
                'total' => number_format(floatval($totalAfterDiscount),2,'.',''),
            ]);
        }
    }

    public function remove_coupon()
    {
        Session::forget('coupon');
        Session::forget('discounts');
        return redirect()->back()->with('success','Cupón removido.');
    }

    public function checkout()
    {
        if (!Auth::check())
        {
            return redirect()->route('login');
        }

        $address = Address::where('user_id',Auth::user()->id)->where('is_default',1)->first();
        return view('checkout',compact('address'));
    }

    public function place_an_order(Request $request, MercadoPagoService $mercadoPagoService)
    {
        $user_id = Auth::user()->id;
        $address = Address::where('user_id',$user_id)->where('is_default',true)->first();

        if(!$address)
        {
            $request->validate([
                'name' => 'required|max:100',
                'phone' => 'required|numeric|digits:10',
                'zip' => 'required|numeric|digits:4',
                'state' => 'required',
                'city' => 'required',
                'address' => 'required',
                'locality' => 'required',
                'landmark' => 'required',
            ], [
                'name.required' => 'El nombre es obligatorio.',
                'name.max' => 'El nombre no puede superar los 100 caracteres.',
                'phone.required' => 'El número de teléfono es obligatorio.',
                'phone.numeric' => 'El número de teléfono debe contener solo números.',
                'phone.digits' => 'El número de teléfono debe tener 10 dígitos.',
                'zip.required' => 'El código postal es obligatorio.',
                'zip.numeric' => 'El código postal debe contener solo números.',
                'zip.digits' => 'El código postal debe tener 4 dígitos.',
                'state.required' => 'La provincia es obligatoria.',
                'city.required' => 'La ciudad o localidad es obligatoria.',
                'address.required' => 'La dirección es obligatoria.',
                'locality.required' => 'El barrio o zona es obligatorio.',
                'landmark.required' => 'La referencia es obligatoria.',
            ]);

            $address = new Address();
            $address->name = $request->name;
            $address->phone = $request->phone;
            $address->zip = $request->zip;
            $address->state = $request->state;
            $address->city = $request->city;
            $address->address = $request->address;
            $address->locality = $request->locality;
            $address->landmark = $request->landmark;
            $address->country = 'Argentina';
            $address->user_id = $user_id;
            $address->is_default = true;
            $address->save();
        }

        $this->setAmountForCheckout();

        // Verificar si la sesión 'checkout' existe
        if (!Session::has('checkout')) {
            return redirect()->route('cart.index')->with('error', 'La sesión de compra ha expirado. Por favor, intenta nuevamente.');
        }

         // Crear un nuevo pedido
        $order = new Order();
        $order->user_id = $user_id;
        $order->subtotal = Session::get('checkout')['subtotal']; // Subtotal del carrito
        $order->discount = Session::get('checkout')['discount']; // Descuento aplicado
        $order->total = Session::get('checkout')['total'];       // Total después del descuento

        // Asociar la dirección del pedido
        $order->name = $address->name;
        $order->phone = $address->phone;
        $order->locality = $address->locality;
        $order->address = $address->address;
        $order->city = $address->city;
        $order->state = $address->state;
        $order->country = $address->country;
        $order->tax = 0;
        $order->canceled_date = null;
        $order->landmark = $address->landmark;
        $order->zip = $address->zip;
        $order->save();

         // Guardar los detalles de cada producto en OrderItem
        foreach (Cart::instance('cart')->content() as $item) {
            $orderItem = new OrderItem();
            $orderItem->product_id = $item->id;        // ID del producto
            $orderItem->order_id = $order->id;        // ID del pedido
            $orderItem->price = $item->price;         // Precio del producto
            $orderItem->quantity = $item->qty;       // Cantidad comprada
            $orderItem->save();
        }

        // Registrar una transacción para este pedido
        $transaction = new Transaction();
        $transaction->user_id = $user_id;
        $transaction->order_id = $order->id;
        $transaction->mode = $request->mode;  // Modo de pago (por ejemplo, "MercadoPago", "Transferencia")
        $transaction->status = 'pending';    // Estado inicial de la transacción
        $transaction->save();

        if ($request->mode === 'mercadopago') {
            return $mercadoPagoService->crearPreferencia([
                'order_id' => $order->id, // Pasar el ID del pedido para referencia externa
                'items' => Cart::instance('cart')->content(),
            ]);
        }

         // Limpieza: Vaciar el carrito y datos de sesión relacionados
        Cart::instance('cart')->destroy();
        Session::forget('checkout');
        Session::forget('coupon');
        Session::forget('discounts');
        Session::put('order_id',$order->id);
        
        // Redirigir al usuario a una página de confirmación
        return redirect()->route('cart.confirmation');
    }

    public function setAmountForCheckout()
    {
        // Verifica si el carrito tiene productos
        if (Cart::instance('cart')->content()->count() <= 0) {
            Session::forget('checkout');
            return;
        }

        // Si hay un cupón aplicado
        if (Session::has('coupon')) {
            Session::put('checkout', [
                'discount' => Session::get('discounts')['discount'], // Descuento aplicado
                'subtotal' => Session::get('discounts')['subtotal'], // Subtotal después del descuento
                'total' => Session::get('discounts')['total'],       // Total después del descuento
            ]);
        } else {
            // Si no hay cupón, usa los valores directos del carrito
            Session::put('checkout', [
                'discount' => 0, // No hay descuento
                'subtotal' => Cart::instance('cart')->subtotal(), // Subtotal directo del carrito
                'total' => Cart::instance('cart')->total(),       // Total directo del carrito
            ]);
        }
    }

    public function order_confirmation()
    {
        // Verificar si la sesión 'order_id' existe
        if (!Session::has('order_id')) {
            return redirect()->route('cart.index')->with('error', 'No se encontró un pedido activo.');
        }

        // Obtener el pedido
        $order = Order::find(Session::get('order_id'));

        // Verificar si el pedido existe
        if (!$order) {
            return redirect()->route('cart.index')->with('error', 'El pedido no existe.');
        }

        return view('order-confirmation', compact('order'));
    }

}
