<div class="col-md-6 col-md-push-3 text-center">
    <h1 class="error404 center">500</h1>
   Ops! Page is not responding. Our team is already dealing with it
    {#{% if session.isAdmin == 1  %}#}
        <div class="well">
                {{ errorMessageForAdmin }}
        </div>
    {#{% endif %}#}
</div>