"use strict";
var KTMagasinsExport = function() {
    var t, e, n, o, r, i, a;
    return {
        init: function() {
            t = document.querySelector("#kt_cond_export_modal"), a = new bootstrap.Modal(t), i = document.querySelector("#kt_cond_export_form"), e = i.querySelector("#kt_cond_export_submit"), n = i.querySelector("#kt_cond_export_cancel"), o = t.querySelector("#kt_cond_export_close"), r = FormValidation.formValidation(i, {
                fields: {
                    date: {
                        validators: {
                            notEmpty: {
                                message: "Date range is required"
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
            }), e.addEventListener("click", (function(t) {
                t.preventDefault(), r && r.validate().then((function(t) {
                    console.log("validated!"), "Valid" == t ? (e.setAttribute("data-kt-indicator", "on"), e.disabled = !0, setTimeout((function() {
                        e.removeAttribute("data-kt-indicator"), Swal.fire({
                            text: "La liste des magasins a été bien exportée!",
                            icon: "success",
                            buttonsStyling: !1,
                            confirmButtonText: "Ok",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        }).then((function(t) {
                            t.isConfirmed && (a.hide(), e.disabled = !1)
                        }))
                    }), 2e3)) : Swal.fire({
                        text: "Sorry, looks like there are some errors detected, please try again.",
                        icon: "error",
                        buttonsStyling: !1,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    })
                }))
            })), n.addEventListener("click", (function(t) {
                t.preventDefault(), Swal.fire({
                    text: "Etes-vous sûr de vouloir annuler?",
                    icon: "warning",
                    showCancelButton: !0,
                    buttonsStyling: !1,
                    confirmButtonText: "Oui, j'annule!",
                    cancelButtonText: "Non, revenir",
                    customClass: {
                        confirmButton: "btn btn-primary",
                        cancelButton: "btn btn-active-light"
                    }
                }).then((function(t) {
                    t.value ? (i.reset(), a.hide()) : "cancel" === t.dismiss && Swal.fire({
                        text: "L'envoi du formulaire a été bien annulé!.",
                        icon: "error",
                        buttonsStyling: !1,
                        confirmButtonText: "Ok",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    })
                }))
            })), o.addEventListener("click", (function(t) {
                t.preventDefault(), Swal.fire({
                    text: "Etes-vous sûr de vouloir annuler?",
                    icon: "warning",
                    showCancelButton: !0,
                    buttonsStyling: !1,
                    confirmButtonText: "Oui, j'annule!",
                    cancelButtonText: "Non, revenir",
                    customClass: {
                        confirmButton: "btn btn-primary",
                        cancelButton: "btn btn-active-light"
                    }
                }).then((function(t) {
                    t.value ? (i.reset(), a.hide()) : "cancel" === t.dismiss && Swal.fire({
                        text: "L'envoi du formulaire a été bien annulé!.",
                        icon: "error",
                        buttonsStyling: !1,
                        confirmButtonText: "Ok",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    })
                }))
            })),
                function() {
                    const t = i.querySelector("[name=date]");
                    $(t).flatpickr({
                        altInput: !0,
                        altFormat: "F j, Y",
                        dateFormat: "Y-m-d",
                        mode: "range"
                    })
                }()
        }
    }
}();
KTUtil.onDOMContentLoaded((function() {
    KTMagasinsExport.init()
}));