<!-- Meta Pixel Code para o evento Purchase -->
<script>
    !function(f,b,e,v,n,t,s) {
        if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};
        if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
        n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t,s)}(window, document,'script',
        'https://connect.facebook.net/en_US/fbevents.js');

    // Inicializa o Pixel com o ID do Pixel do Facebook a partir do .env
    fbq('init', '{{ env('PIXEL_ID') }}'); // Puxando o ID do Pixel do arquivo .env

    // Chamando a função do evento Purchase com valores fixos
    fbq('track', 'Purchase', {
        value: 80.00, // Valor da compra em reais
        currency: 'BRL' // Moeda da compra
    });
</script>
<noscript>
    <img height="1" width="1" style="display:none"
    src="https://www.facebook.com/tr?id={{ env('PIXEL_ID') }}&ev=Purchase&value=80.00&currency=BRL&noscript=1"
    />
</noscript>
