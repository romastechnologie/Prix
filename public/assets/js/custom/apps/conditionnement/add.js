"use strict";
var KTAppSaveConditionnement = function() {
    return {
        init: function() {
            (() => {
                let e;
                const t = document.getElementById("condi-form"), o = document.getElementById("condi_submit");
                e = FormValidation.formValidation(t, {
                    fields: {
                        "conditionnement[codeCond]": {
                            validators: {
                                notEmpty: {
                                    message: "Le code est obligatoire"
                                }
                            }
                        },
                        "conditionnement[libelleCond]": {
                            validators: {
                                notEmpty: {
                                    message: "Le libellé est obligatoire"
                                }
                            }
                        }
                    },
                    plugins: {
                        trigger: new FormValidation.plugins.Trigger,
                        bootstrap: new FormValidation.plugins.Bootstrap5({
                            rowSelector: ".fv-row",
                            eleInvalidClass: "",
                            eleValidClass: ""
                        })
                    }
                }),
                o.addEventListener("click", (a => {
                    a.preventDefault(), e && e.validate().then((function(e) {
                        "Valid" == e ? (
                            o.setAttribute("data-kt-indicator", "on"),
                            o.disabled = !0,
                            setTimeout((function() {
                                o.removeAttribute("data-kt-indicator"),
                                Swal.fire({
                                    text: "Le formulaire a été soumis avec succès!",
                                    icon: "success",
                                    buttonsStyling: !1,
                                    confirmButtonText: "Ok",
                                    customClass: {
                                        confirmButton: "btn btn-primary"
                                    }
                                }).then((function(e) {
                                    console.log(t.dataset.action);

                                    // Appel de l'url de création ou d'édition d'un objet en lien avec
                                    $.ajax({
                                        url: t.dataset.action,
                                        type: 'GET',
                                        dataType: 'json',
                                        processData: false,
                                        contentType: false,
                                        data: {cle: "Ceci est un test !!"},
                                        beforeSend: function () { // Before we send the request, remove the .hidden class from the spinner and default to inline-block.
                                            $("body").addClass("loading");
                                        },
                                        success: function (data) {
                                            console.log('Résultat : ', data);
                                        },
                                        complete: function () { // Before we send the request, remove the .hidden class from the spinner and default to inline-block.
                                            $("body").removeClass("loading");
                                        },
                                    });
                                    //e.isConfirmed && (o.disabled = !1, window.location = t.getAttribute("data-kt-redirect"))
                                }))
                            }), 2e3)
                        ) : Swal.fire({
                            text: "Désolé, il semble qu'il y ait des erreurs détectées, veuillez réessayer.",
                            icon: "error",
                            buttonsStyling: !1,
                            confirmButtonText: "Ok",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        })
                    }))
                }))
            })()
        }
    }
}();
KTUtil.onDOMContentLoaded((function() {
    KTAppSaveConditionnement.init()
}));