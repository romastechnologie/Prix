
    function Addandedit(form, container = null, formNew = null) {
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            beforeSend: function () {
                const loadingEl = document.createElement("div");
                document.body.prepend(loadingEl);
                loadingEl.classList.add("page-loader");
                loadingEl.classList.add("flex-column");
                loadingEl.classList.add("bg-dark");
                loadingEl.classList.add("bg-opacity-25");
                loadingEl.innerHTML = `
                    <span class="spinner-border text-primary" role="status"></span>
                    <span class="fs-6 fw-semibold mt-5" style="color:#ffffff">Chargement...</span>
                `;
                document.body.classList.add('page-loading');
                document.body.setAttribute('data-kt-app-page-loading', "on")
            },
            success: function (msg) {
                form.trigger("reset");
                form.find('select').each(function (i, elt) {
                    $(elt).val(null).select2();
                });
                if(formNew != null)
                    $(container).html(formNew);
                toastr.success(msg);
                if(datatable != null)
                    datatable.ajax.reload();
            },
            error: function(e){
                toastr.error('erreur');
            },
            complete: function () {
                document.body.classList.remove('page-loading');
                document.body.removeAttribute('data-kt-app-page-loading');
            },
        });
    }
