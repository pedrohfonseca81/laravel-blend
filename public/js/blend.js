window.Echo = new Echo({
    broadcaster: 'pusher',
    key: 'sua-chave',
    cluster: 'seu-cluster',
    forceTLS: true
});

window.Echo.channel('updated-files')
    .listen('FileChangedListener', (event) => {
        //
    });