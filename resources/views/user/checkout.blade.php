<p>決済ページへリダイレクトします。</p> 
<script src="https://js.stripe.com/v3/"></script>
<script>
    const publicKey = '{{ $publicKey }}'
    const stripe = Stripe(publicKey)

    window.onload = function() { 
        // redirectToCheckoutでチェックアウトページに遷移させる
        stripe.redirectToCheckout({             
        sessionId: '{{ $session->id }}'         
        }).then(function (result) {             
            // NGだった場合user.cart.indexに戻し、在庫を戻す
            window.location.href = "{{ route('user.cart.index') }}";         
            });
    } 

</script>