<div id="{{ $event }}-event-render">ddd</div>

<script>
    document.addEventListener("{{ $event }}", (event) => {
        var render = document.getElementById("{{ $event }}-event-render");
        if (render) {
            render.innerHTML = event.detail;
        }
    });
</script>
