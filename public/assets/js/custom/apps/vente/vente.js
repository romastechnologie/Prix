var gestionVente = function () {
    /**
     * Vérifie s'il s'agit bien d'un élément HTML
     * @param element
     * @returns {boolean}
     */
    const isElement = (element) => {
        return element instanceof Element || element instanceof HTMLDocument;
    }
    const ajaxDeleteResult = (form, data) => {
        console.log(form);
        form.remove();
        if(data != null)
        {
            console.log(data);
        }
    }
    const addFormDeleteLink = (form) => {
        if (isElement(form)) {
            console.log(form);
            const removeFormButton = form.querySelectorAll('button.remove');
            let succes = false;
            removeFormButton.forEach(btnremove => {
                btnremove.addEventListener('click', (e) => {
               /* $.ajax({
                    type: "POST",
                    url: removeFormButton.dataset.url,
                    data: {token: removeFormButton.dataset.token},
                    dataType: "json",
                    beforeSend: function () { // Before we send the request, remove the .hidden class from the spinner and default to inline-block.
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
                    success: function(data){
                        succes = true;
                        /*ajaxDeleteResult(form, data);
                    },
                    complete: function () {
                        document.body.classList.remove('page-loading');
                        document.body.removeAttribute('data-kt-app-page-loading');
                    }
                });*/
                form.remove();
            });
        })
        }
    }
    /**
     * Permet d'ajouter un ou plusieurs formulairs imbriqués
     * à un formulaire parent après un clic sur le bouton d'ajout
     * @param e
     */
    const addFormToCollection = (e) => {
        if(container != null)
        {
           // var container = document.querySelector('[data-call-form="ajax"]');
           
            /**
             * Récupérer l'élément conteneur de formulaires imbriqués
             */
            const collectionHolder = container.querySelector('.' + e.currentTarget.dataset.collectionHolderClass);
            /**
             * Récupérer la forme brute du formulaire à imbriqué
             */
            const prototype = collectionHolder.dataset.prototype;
            const div = document.createElement('div');
            div.innerHTML = prototype.replace(
                /__name__/g,
                collectionHolder.dataset.index
            );
            /**
             * Ajouter la forme html du formulaire imbriqué au conteneur
             */
            collectionHolder.appendChild(div);
            addFormDeleteLink(div);
            $(collectionHolder).find('[data-control="select2"]').select2();
            /** Incrémentation de l'index après insertion du formulaire imbriqué **/
            collectionHolder.dataset.index++;

            $.each($($(container).find('select')), function (i, elt) {
                console.log('dfvdfvd',elt);
                let select = $(elt);
                name = select.attr('name');
                let blocproduit=select.parent().parent();
                
                if(name.includes('[produit]')){
                    select.parent().next()
                    select.change(function () {
                        var textselected = $(this).find('option:selected').text();
                        var tab = textselected.split(" ");
                        var prix = tab[tab.length -1];
                        blocproduit.find('input[name*="prix"]').val(parseFloat(prix));
                        blocproduit.find('input[name*="qtite"]').val(1);    
                        blocproduit.find('input[name*="montant"]').val(parseFloat(prix));
                        
                        blocproduit.find('input').keyup(function () {
                            var montant = 0;
                            var prix= parseFloat(blocproduit.find('input[name*="prix"]').val());
                            var qtite= parseFloat(blocproduit.find('input[name*="qtite"]').val());
                            montant= prix*qtite
                            console.log('montant',montant);
                            console.log('prix',prix);
                            console.log('qtite',qtite);
                            blocproduit.find('input[name*="montant"]').val(montant);
                            var totalsomme = 0;
                            let montantParProduit = $(".montant");
                            for ( let i = 0; i < montantParProduit.length; i++) {
                                if($(montantParProduit[i]).val() !=''){
                                    totalsomme += parseFloat($(montantParProduit[i]).val());
                                }
                            }
                            $(container).find('input[name*="montantHt"]').val(totalsomme)
                            $(container).find('input[name*="montantTtc"]').val((totalsomme*0.82).toFixed(2))
                            $(container).find('input[name*="montantTotal"]').val((totalsomme*0.82).toFixed(2))
                        });
                        var totalsomme = 0;
                        let montantParProduit = $(".montant");
                        for ( let i = 0; i < montantParProduit.length; i++) {
                            if($(montantParProduit[i]).val() !=''){
                                totalsomme += parseFloat($(montantParProduit[i]).val());
                            }
                        }
                        $(container).find('input[name*="montantHt"]').val(totalsomme)
                        $(container).find('input[name*="montantTtc"]').val((totalsomme*0.82).toFixed(2))
                        $(container).find('input[name*="montantTotal"]').val((totalsomme*0.82).toFixed(2))
                    })
                }
            })
        }
    }
    function vente() {
        if(container != null)
        {
                //console.log('container',container.querySelectorAll('.add_caisse_item_link'));
              /*  $(container).find('.add_caisse_item_link').each(function () {
                    $(this)
                })*/
                container.querySelectorAll('.add_caisse_item_link').forEach(btn => {
                console.log("click",btn);
                btn.addEventListener("click", addFormToCollection);
                const holders = document.querySelectorAll('.' + btn.dataset.collectionHolderClass);
                    holders.forEach(h => {
                        const divs = h.querySelectorAll('div');
                        divs.forEach(div => {
                            addFormDeleteLink(div);
                        });
                    });
                });
                console.log("vfvfvf",container);
                

               /* container.querySelectorAll('.add_caisse_item_link2').forEach(btn => {
                    console.log("click",btn);
                    btn.addEventListener("click", addFormToCollection);
                    const holders = document.querySelectorAll('.' + btn.dataset.collectionHolderClass);
                        holders.forEach(h => {
                            const divs = h.querySelectorAll('div');
                            divs.forEach(div => {
                                addFormDeleteLink(div);
                            });
                        });
                    });*/
        }

    }
    var handlelistes = () => {
        // Select all delete buttons
        const buttons = document.querySelectorAll('[data-kt-entity-table-filter="list"]');
        const bloc = document.querySelector('[data-call-form="ajax"]');
        console.log(buttons);
        console.log(bloc);
        buttons.forEach(d => {
            d.addEventListener('click', function (e) {
                e.preventDefault();
                console.log(console.log(e.target));
                $.ajax({
                    type: "GET",
                    url: e.target.dataset.url,
                    dataType: "json",
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
                    success: function(data){
                        $(bloc).html(data.html);
                        const form = bloc.querySelector('form');
                        if(form){
                            form.querySelectorAll('select').forEach(s => {
                                $(s).select2();
                            });
                            gestionVente.init();
                        }else{
                            KTDatatablesServerSide.init()
                        }
                       /* if(datatable != null)
                            datatable.ajax.reload();*/

                        /*const btn = bloc.querySelector('button[type="submit"]');
                            btn.addEventListener("click", function(e){
                                e.preventDefault();
                                Addandedit($(form), bloc, data.new);
                            });
                       /* const btnnew = bloc.querySelector('[data-new-vente="new"]');
                            btnnew.addEventListener("click", function(e){
                                e.preventDefault();
                                $(bloc).html(data.new);
                            });*/
                        //KTAll.init();
                    },
                    complete: function () {
                        document.body.classList.remove('page-loading');
                        document.body.removeAttribute('data-kt-app-page-loading');
                    }
                });
            })
        });
    }
    return {
        init: function () {
            handlelistes();
            vente();
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    gestionVente.init();
});