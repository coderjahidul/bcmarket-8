(function($) {

($.fn.serializeObject = function () {
    "use strict";
    var e = {};
    return (
        $.each(this.serializeArray(), function (t, a) {
            var i = e[a.name];
            null != i ? ($.isArray(i) ? i.push(a.value) : (e[a.name] = [i, a.value])) : (e[a.name] = a.value);
        }),
        e
    );
}),
    $(function () {
        var e,
            t,
            a = !1;
        ($.datepicker.regional.ru = {
            closeText: lang.close,
            prevText: lang.prev,
            nextText: lang.next,
            currentText: lang.today,
            monthNames: lang.monthNames,
            monthNamesShort: lang.monthNamesShort,
            dayNames: lang.dayNames,
            dayNamesShort: lang.dayNamesShort,
            dayNamesMin: lang.dayNamesMin,
            weekHeader: lang.weekHeader,
            dateFormat: "dd.mm.yy",
            firstDay: 1,
            isRTL: !1,
            showMonthAfterYear: !1,
            yearSuffix: "",
        }),
            $.datepicker.setDefaults($.datepicker.regional.ru),
            $(".head-category-navigation button").click(function () {
                var e = "block";
                $(".head-menu").is(":visible") && (e = "none"), $(".head-menu, .shadow").css({ display: e });
            }),
            $(".refill-icons>div div").click(function () {
                $(this).closest(".refill-icons").find(".active-refill").removeClass("active-refill"),
                    $(this).addClass("active-refill"),
                    $('.lk-refill-form input[name="payment_system"]').val($(this).data("id")),
                    $(this).data("id") == psQiwi ? addPhoneInput() : removePhoneInput();
            }),
            $("#ps").change(function (e) {
                $("#wallet_number").val(this.options[this.selectedIndex].dataset.wallet);
            });
        var i = $("input.lk-refill-form__input");
        $(".inc-sum").click(function () {
            var e = parseFloat(i.val()) + 100;
            i.val(e), i.trigger("change");
        }),
            $(".dec-sum").click(function () {
                var e = i.attr("min"),
                    t = parseFloat(i.val()),
                    a = t <= e ? t : t - 100;
                i.val(a), i.trigger("change");
            }),
            document.body.clientWidth < 770 &&
                $(".cabinet-open").click(function (e) {
                    e.preventDefault(),
                        a
                            ? ($(".cabinet-sub-menu").css({ width: "220px", display: "none" }), $(".cabinet-menu").css({ position: "relative" }))
                            : ($(".cabinet-sub-menu").css({ width: "100%", display: "block" }), $(".cabinet-menu").css({ position: "static" })),
                        (a = !a);
                }),
            $(".partner-menu button").click(function () {
                $("#content .partner-menu ul.partner-menu-items").toggleClass("partner-menu-mobile-hide");
            }),
            $(".lk-menu button").click(function () {
                $(".lk-menu ul").toggleClass("lk-menu-mobile-hide");
            }),
            $("body").on("click", ".shadow", function () {
                var e = ".head-menu, .shadow";
                document.body.clientWidth < 991 && (e += ", .hdr-middle"), $(e).css({ display: "none" }), $("body").css({ overflow: "auto" });
            }),
            $(".open-menu").click(function () {
                $(".hdr-middle").is(":visible") ? ($(".hdr-middle").css({ display: "none" }), $(".shadow").css({ display: "none" })) : ($(".hdr-middle").css({ display: "block" }), $(".shadow").css({ display: "block" }));
            }),
            $(".head-menu > li > a").hover(function () {
                var t = $(this),
                    a = this.dataset.tooltip ? "<p>" + this.dataset.tooltip + "</p>" : "";
                $(this).next("ul").find(".category-tooltip").html(a),
                    o(),
                    (e = setTimeout(function () {
                        $(".head-menu a").removeClass("active"), $(".head-menu ul").css({ display: "none" }), t.addClass("active"), t.next("ul").css({ display: "flex" });
                    }, 300));
            }),
            $(".head-menu").on("mouseover", "ul", function () {
                o();
            }),
            $(".head-menu").on("mouseover", "ul a", function () {
                var e = this.dataset.tooltip,
                    t = $(this).parents("ul").find(".category-tooltip"),
                    a = $(this).parents("ul").prev().attr("data-tooltip");
                e ? t.html("<p>" + e + "</p>") : a ? t.html("<p>" + a + "</p>") : t.text("");
            }),
            $(".head-menu").on("mouseleave", "ul a", function () {
                var e = $(this).parents("ul").find(".category-tooltip"),
                    t = $(this).parents("ul").prev().attr("data-tooltip");
                t ? (e.html("<p>" + t + "</p>"), console.log(t)) : e.text("");
            }),
            $(".head-menu").hover("", function () {
                o(),
                    (t = setTimeout(function () {
                        $(".head-menu a").removeClass("active"), $(".head-menu ul").css({ display: "none" });
                    }, 300));
            });
        var o = function () {
            clearTimeout(e), clearTimeout(t);
        };
        document.body.clientWidth < 991 &&
            ($(".darr > a").click(function (e) {
                e.preventDefault(), $(this).next().slideToggle(300), $(this).parent().toggleClass("active");
            }),
            $(".head-menu a").click(function (e) {
                var t = $(this).next("ul");
                if (0 == t.length) return !0;
                e.preventDefault(), t.hasClass("show-item") ? t.removeClass("show-item") : ($(".show-item").removeClass("show-item"), t.addClass("show-item"));
            }),
            $("button.search_trigger").click(function (e) {
                $(".head-search").slideToggle(300), $(this).toggleClass("active");
            }));
    });
var busy = 0,
    already_animated = !1,
    psQiwi = 17;
(declofnum = function (e, t) {
    return t[e % 100 > 4 && e % 100 < 20 ? 2 : [2, 0, 1, 1, 1, 2][Math.min(e % 10, 5)]];
}),
    (windowHeight = function () {
        var e = document.documentElement;
        return self.innerHeight || (e && e.clientHeight) || document.body.clientHeight;
    }),
    (_notify = function (e) {
        var t = e.type || "notify",
            a = e.icon || "loading",
            i = document.getElementById("_notify") ? $("#_notify") : $('<div class="_notify" id="_notify"></div>').appendTo("body"),
            o = "";
        switch (a) {
            case "ok":
                o += '<img src="/img/notify/ic-ok.png" alt=""> ';
                break;
            case "loading":
                o += '<img src="/img/notify/ajax-loader.gif" alt=""> ';
                break;
            case "alert":
                o += '<img src="/img/notify/ic-alert.png" alt=""> ';
        }
        (o += e.message), i.html(o);
        var n = i.height();
        switch ((i.is(":hidden") && i.css("top", -n).animate({ top: 0, opacity: "show" }, 200), t)) {
            case "notify":
                setTimeout(function () {
                    i.animate({ top: -n, opacity: "hide" }, 200);
                }, 3e3);
        }
    }),
    (_notify_modal = function (e) {
        var t = e.icon || "loading",
            a = "",
            i = e.href_link;
        switch (t) {
            case "ok":
                a += '<img src="/img/notify/ic-ok.png" alt=""> ';
                break;
            case "loading":
                a += '<img src="/img/notify/ajax-loader.gif" alt=""> ';
                break;
            case "alert":
                a += '<img src="/img/notify/ic-alert.png" alt=""> ';
        }
        a += e.message;
        var o = $('<p id="notify_modal"></p>')
            .appendTo("body")
            .dialog({
                modal: !0,
                width: $("body").width() > 800 ? 800 : "95%",
                close: function () {
                    i ? (location.href = i) : o.remove();
                },
                buttons: {
                    OK: function () {
                        i ? (location.href = i) : o.remove();
                    },
                },
                open: function () {
                    $("#notify_modal").html(a),
                        $(".ui-widget-overlay").click(function () {
                            i ? (location.href = i) : o.remove();
                        });
                },
            });
    }),
    (_modal = function (e) {
        $(".forgot-open").removeClass("forgot-open"),
            $(".reg-open").removeClass("reg-open"),
            $(".log-open").removeClass("log-open"),
            $(".modal-shadow .modal-body .t-block").text(e.title),
            $(".modal-shadow .modal-body .message").text(e.message),
            $(".modal-shadow").addClass("modal-open");
    }),
    (makedate = function (e, t, a) {
        var i = new Date(1e3 * e),
            o = new Date(),
            n = ((o = new Date(o.getFullYear(), o.getMonth(), o.getDate(), 0, 0, 0, 0)), "");
        return (
            (n = (e = parseInt(e)) < (o = parseInt(o.getTime() / 1e3)) && e >= o - 86400 && a ? lang.yesterday : e >= o && e < o + 86400 && a ? lang.today : i.getDate() + " " + lang.monthDay[i.getMonth()] + " " + i.getFullYear()),
            1 == t
                ? (n += lang.timeSeparate + i.getHours() + ":" + (1 == i.getMinutes().toString().length ? "0" + i.getMinutes() : i.getMinutes()))
                : 2 == t && ((days = lang.dayNames), (n += " / " + days[i.getDay()] + " / " + i.getHours() + ":" + i.getMinutes())),
            n
        );
    }),
    (validateEmail = function (e) {
        return /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(e);
    }),
    (centerDialog = function (e, t) {
        (e.outerHeight() + e.offset().top > $("body").height() || t) && e.dialog("option", "position", { my: "center", at: "center", of: window });
    }),
    (makenumber = function (e, t) {
        return t ? e.toString().replace(/(\+7)?([0-9]{3})([0-9]{3})([0-9]{2})([0-9]{2})/g, "$2-$3-$4-$5") : e.toString().replace(/(\+7)?([0-9]{3})([0-9]{3})([0-9]{2})([0-9]{2})/g, "($2) $3-$4-$5");
    }),
    (htmlspecialchars = function (e) {
        return e.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
    }),
    (number_format = function (e) {
        return e.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
    }),
    (request = function (o, callback) {
        return (
            $.post("/req/" + o.act + ".php", o, function (response) {
                eval(response), "function" == typeof callback && callback.apply();
            }),
            !1
        );
    }),
    (invalidate = function (e, t) {
        for (var a in ($("div.error").remove(), e)) {
            if (document.getElementById("s2id_" + a)) var i = $("#s2id_" + a).offset();
            else i = $('[name="' + a + '"]', t).offset();
            var o = $('<div class="error dn"><em></em><p>' + e[a] + "</p></div>").appendTo("body");
            o.css({ top: i.top - o.height() - 15, left: i.left + 5, display: "block" });
        }
        recaptcha_reset();
    }),
    (invalidate_email = function (e, t) {
        $("#" + t + "_form .form-line:first").addClass("line-alert"), $("." + t + "-error").text(e), $("." + t + "-error").show(), recaptcha_reset();
    }),
    (form = {
        invalidate: function (e, t) {
            if (void 0 !== e.errors.validate_bids && 1 === e.errors.validate_bids) showErrorsValidateFormatBids(e.errors.data);
            else
                for (var a in ($("div.error").remove(), e.errors)) {
                    var i = $('[name="' + a + '"]', t);
                    $('<div class="error" id="error_' + a + '"><em></em><p>' + e.errors[a] + "</p></div>")
                        .appendTo("body")
                        .position({ my: "left bottom+4", at: "left top", of: i }),
                        i.bind("keydown", function () {
                            document.getElementById("error_" + a) && $("#error_" + a).remove(), i.unbind("keydown");
                        });
                }
        },
        send: function (e, t) {
            $('button[type="submit"]', e).prop("disabled", !0);
            var a = $(e).serializeObject();
            return (
                $.post("/req/" + (void 0 === a.act ? "form" : a.act) + ".php", a, function (a) {
                    if (($('button[type="submit"]', e).prop("disabled", !1), void 0 === a.code)) return alert(lang.requestError), !1;
                    0 !== parseInt(a.code) ? (void 0 !== a.errors && Object.keys(a.errors).length ? form.invalidate(a, e) : void 0 !== a.errormsg && alert(a.errormsg)) : "function" == typeof t && t.call(this, a);
                }),
                !1
            );
        },
    }),
    (authorisation = function () {
        var e = $("#login_form");
        ajaxform(e.get(0));
    }),
    (authorisation_page = function () {
        var e = $("#login_form_page");
        ajaxform(e.get(0));
    }),
    (registration_page = function () {
        var e = $("#registration_form_page");
        ajaxform(e.get(0));
    }),
    (registration = function (e) {
        e = e || "#registration_form";
        var t = $(e);
        ajaxform(t.get(0));
    }),
    (forgot_password = function () {
        var e = $("#forgot_form");
        ajaxform(e.get(0));
    }),
    (ticket = function () {
        var e = $("#new_ticket_form");
        ajaxform(e.get(0));
    }),
    (ajaxform = function (el, callback) {
        var formdata = $(el).serializeObject();
        if ("user_registration" === formdata.section) {
            var action = "registration";
            if (!validateEmail(formdata.email)) return invalidate({ email: lang.invalidEmail }, el), !1;
            try {
                if ($.trim(formdata.email) === $.trim(formdata.password)) return invalidate({ password: lang.invalidPasswordReg }, el), !1;
                if ($.trim(formdata.password) !== $.trim(formdata.password2)) return invalidate({ password2: lang.invalidPasswordsNotEquals }, el), !1;
            } catch (e) {
                return console.log(e), !1;
            }
        }
        if ("my" === formdata.section)
            try {
                if (($("#form-front-user").find("div.error").remove(), void 0 !== formdata.new_password && formdata.new_password.length && $.trim(formdata.email) === $.trim(formdata.new_password)))
                    return $('#form-front-user [name="new_password"]').after('<div class="error">' + lang.invalidResetPasswordReg + "</div>"), !1;
            } catch (e) {
                return console.log(e), !1;
            }
        if ("auth" === formdata.section) {
            var action = "login";
            formdata.after_login_href = sessionStorage.getItem("after_login_href");
        }
        return (
            $('button[type="submit"]').attr("disabled", "disabled"),
            $.post("/req/" + (void 0 === formdata.act ? "form" : formdata.act) + ".php", formdata, function (response) {
                $('button[type="submit"]').removeAttr("disabled");
                try {
                    if (((parsedResponse = $.parseJSON(response)), void 0 !== parsedResponse.errorsOrderTicket)) return showErrorsValidateForm($('#new_ticket_form [name="order_id_client"]'), parsedResponse.errorsOrderTicket), !1;
                    parsedResponse && 1 == parsedResponse.code
                        ? (void 0 !== parsedResponse.errors.validate_bids && 1 === parsedResponse.errors.validate_bids && showErrorsValidateFormatBids(parsedResponse.errors.data),
                          parsedResponse.errormsg && toast(parsedResponse.errormsg, "danger"),
                          invalidate_email(parsedResponse.content, action))
                        : parsedResponse && "{" == response.charAt(0) && invalidate(parsedResponse, el);
                } catch (e) {
                    eval(response), $("div.error").remove(), "function" == typeof callback && callback.call(), recaptcha_reset();
                }
            }),
            !1
        );
    }),
    (ajaxformDelivery = function (el, callback) {
        
        var input_data = $(el).serialize();

        console.log(input_data);

        $.ajax({
            type: "POST",
            url: my_ajax_object.ajax_url,
            dataType : 'json',
            data: input_data,
            success: function(response) {
                console.log(response);

                window.location.href = "/partner/offers";

            }
        });

    }),
    (collapsible_toggle = function (e) {
        var t = e.nextElementSibling;
        "none" == t.style.display ? ($(t).slideDown("slow"), (e.innerHTML = lang.collapse)) : ($(t).slideUp("slow"), (e.innerHTML = lang.expand));
    }),
    (bids = {
        show_supplier_dialog: function (e) {
            var t = $("<p></p>")
                .appendTo("body")
                .dialog({
                    title: lang.partnerContacts,
                    modal: !0,
                    width: $("body").width() > 400 ? 400 : "95%",
                    close: function () {
                        t.remove();
                    },
                    open: function () {
                        $(".ui-widget-overlay").click(function () {
                            t.remove();
                        }),
                            $.post("/req/bids.php", { do: "bid_contact", bid_id: e }, function (e) {
                                var a = "";
                                if (0 == e.code) {
                                    var i = e.contact,
                                        o = $('<table class="list zebra ac"/>');
                                    o.append("<tr><th>" + lang.name + "</th><th>Email</th><th>" + lang.contact + "</th></tr>"), (a = o.append("<tr><td>" + i.name + "</td><td>" + i.email + "</td><td>" + i.contact + "</td></tr>"));
                                } else a = lang.contactsNoFound;
                                t.html(a);
                            });
                    },
                });
        },
        form_toggle: function (e) {
            -1 == parseInt(e) ? $("#new_bid_form:hidden").slideDown("slow") : ($("#new_bid_form:visible").slideUp("slow"), parseInt(e) > 0 && bids.upload_dialog(e));
        },
        list: function (e, t, a) {
            var i = lang.accountTypes[a],
                o = $('<div title="' + i + '"><p><img src="'+ my_ajax_object.template_url +'/img/ajax-loader.gif" alt=""></p></div>').dialog({
                    autoOpen: !0,
                    open: function () {
                        $(".ui-widget-overlay").click(function () {
                            o.remove(), (document.body.style.overflow = "inherit");
                        }),
                            (document.body.style.overflow = "hidden");
                    },
                    close: function () {
                        document.body.style.overflow = "inherit";
                    },
                    modal: !0,
                    width: $("body").width() > 900 ? 900 : "95%",
                    maxHeight: "90%",
                });
                console.log(my_ajax_object.ajax_url);
                console.log(my_ajax_object.template_url);

                $.ajax({
                    type: "POST",
                    url: my_ajax_object.ajax_url,
                    dataType : 'html',
                    data: {
                        action: 'item_list', 
                        bid_id: e, 
                        order_id: t, 
                        mode: a, 
                    },
                    success: function(response) {
                        console.log(response);
                        centerDialog(o.html(response), 1);
                    }   
                });

            $.post("/req/bids.php", { do: "list", bid_id: e, order_id: t, mode: a }, function (e) {
                if (0 == e.code) {
                    lang.id;
                    var t = '<table class="list zebra ac"><tr><th>#</th><th>' + lang.account + "</th><th>" + lang.status + "</th></tr>";
                    for (var a in e.list) {
                        if (0 == parseInt(e.list[a].order_link_id)) var i = lang.free;
                        else i = lang.sold;
                        var n = parseInt(a) + 1;
                        t += "<tr" + (parseInt(e.list[a].invalid) ? ' style="background-color:#f99"' : "") + "><td>" + n + "</td><td>" + e.list[a].data + "</td><td>" + i + "</td></tr>";
                    }
                    (t += "</table>"), centerDialog(o.html(t), 1);
                } else alert(e.errormsg);
            });
        },
        new_bid: function (e) {

            

            var input_data = $('#new_bid_form').serialize();

            console.log(input_data);

            $.ajax({
                type: "POST",
                url: my_ajax_object.ajax_url,
                dataType : 'json',
                data: input_data,
                success: function(response) {
                    console.log(response);

                    window.location.href = "/partner";

                }
            });

            var t = !1;
            return (
                $('#new_bid_form [name="format[]"]').length &&
                    $.each($('#new_bid_form [name="format[]"]'), function (e, a) {
                        $(a).val().length > maxCountSymbolsBidsFormatField &&
                            (void 0 !== lang.maxCountSymbolsBidsFormatField ? alert(lang.maxCountSymbolsBidsFormatField) : alert("The maximum number of characters in the Format field is 255"), (t = !0));
                    }),
                !t &&
                    (form.send(e, function () {
                        var e = lang.id;
                        (e && void 0 !== e) || (e = "ru"), (location.href = "/" + e + "/partner");
                    }),
                    !1)
            );
        },
        new_bid_dialog: function (e) {
            var t = $(
                '<div id="upload_dialog" title="' +
                    lang.loadAccounts +
                    '"><p><input type="text" name="partner_cost" id="partner_cost" value="" placeholder="' +
                    lang.cost +
                    '" required="required"></p><p><textarea name="accounts" id="accounts" style="height:40vh" placeholder="' +
                    lang.setAccounts +
                    '"></textarea></p></div>'
            )
                .appendTo("body")
                .dialog({
                    autoOpen: !0,
                    close: function () {
                        t.remove();
                    },
                    buttons: [
                        {
                            text: lang.cancel,
                            click: function () {
                                t.remove();
                            },
                        },
                        {
                            text: lang.upload,
                            click: function () {

                                console.log('You are at the right place!');
                                console.log(e);
                                console.log(document.getElementById("accounts").value); 

                                $.post(
                                    "/req/bids.php",
                                    {
                                        do: "new_bid_with_accounts",
                                        item_id: e,
                                        accounts: function () {
                                            return document.getElementById("accounts").value;
                                        },
                                        partner_cost: function () {
                                            return document.getElementById("partner_cost").value;
                                        },
                                    },
                                    function (e) {
                                        0 == parseInt(e.code)
                                            ? (t.html(e.message),
                                              t.dialog("option", {
                                                  buttons: [
                                                      {
                                                          text: lang.close,
                                                          click: function () {
                                                              t.remove();
                                                          },
                                                      },
                                                  ],
                                              }))
                                            : alert(e.errormsg);
                                    }
                                );
                            },
                        },
                    ],
                    open: function () {
                        $(".ui-widget-overlay").click(function () {
                            t.remove();
                        });
                    },
                    modal: !0,
                    width: $("body").width() > 800 ? 800 : "95%",
                    maxHeight: "90%",
                });
        },
        change_parent_item: function (e) {
            var t = e.options[e.selectedIndex].value,
                a = $("#child");
            a.html(""),
                _categories[t]
                    ? ($.each(_categories[t].items, function (e, t) {
                          a.append('<option data-hidden="' + t.hidden + '" value="' + t.id + '">' + t.name + "</option>");
                      }),
                      a.prop("disabled", !1))
                    : a.prop("disabled", !1);
        },
        remove_unsold: function (e) {
            var t = lang.removeUnsoldAccounts;
            if (!confirm(t)) return !1;

            $.ajax({
                type: "POST",
                url: my_ajax_object.ajax_url,
                dataType : 'html',
                data: {
                    action: 'remove_unsold', 
                    bid_id: e, 
                },
                success: function(response) {
                    console.log(response);
                    location.reload(true);
                }
            });
            $.post("/req/bids.php", { do: "remove_unsold", bid_id: e }, function (e) {
                0 == e.code ? location.reload() : alert(e.errormsg);
            });
        },
        set_paid: function (e) {
            return (
                $(e)
                    .find('[type="submit"]')
                    .replaceWith('<img src="/img/ajax-loader.gif" alt="' + lang.load + '">'),
                setTimeout(function () {
                    form.send(e, function (e) {
                        document.getElementById("bid_content").innerHTML = e.html;
                    });
                }, 500),
                !1
            );
        },
        set_status: function (e) {
            document.getElementById("status_id" + e.bid_id) && (document.getElementById("status_id" + e.bid_id).value = e.status_id),
                document.getElementById("status_name" + e.bid_id) && (document.getElementById("status_name" + e.bid_id).innerHTML = e.status_name),
                1 !== parseInt(e.status_id) && document.getElementById("upload" + e.bid_id) && $("#upload" + e.bid_id).remove();
        },
        upload_dialog: function (e) {
            var a = '<div class="upload_warning">' + lang.mustRead + '<a href="/' + lang.id + '/accounts_faq" target="_blank">' + lang.accPlacingRequirements + "</a><br>" + lang.uploadRejection + "</div>";
            t = $('<div id="upload_dialog" title="' + lang.uploadLabel + '" style="display:none">' + a + '<textarea name="accounts" id="accounts" style="height:40vh"></textarea></div>')
                .appendTo("body")
                .dialog({
                    autoOpen: !0,
                    buttons: [
                        {
                            text: lang.cancel,
                            click: function () {
                                t.dialog("close"), t.remove();
                            },
                        },
                        {
                            text: lang.upload,
                            click: function () {

                                console.log('You are at the right place!');
                                console.log(e);
                                console.log(document.getElementById("accounts").value);

                                $.ajax({
                                    type: "POST",
                                    url: my_ajax_object.ajax_url,
                                    dataType : 'json',
                                    data: {
                                        action: 'upload_accounts', 
                                        bid_id: e, 
                                        accounts:  document.getElementById("accounts").value
                                    },
                                    success: function(response) {
                                        console.log(response);
                                        console.log('Uploaded');

                                        //window.location.href = "/partner";
                                        $('#upload_dialog').html('The Accounts are queued for uploading. This usually takes only a few minutes. You will receive a notification by email after the accounts are uploaded. ');


                                        $('#status_name' + response.bid_id).text('Checking Accounts');
                                        $('#status_name' + response.bid_id).next().hide();

                                        t.dialog("option", {
                                              buttons: [
                                                  {
                                                      text: lang.close,
                                                      click: function () {
                                                          t.remove();
                                                      },
                                                  },
                                              ],
                                        });

                                    }
                                });

                                $.post(
                                    "/req/bids.php",
                                    {
                                        do: "upload_accounts",
                                        bid_id: e,
                                        accounts: function () {
                                            return document.getElementById("accounts").value;
                                        },
                                    },
                                    function (e) {
                                        0 == parseInt(e.code)
                                            ? ((document.getElementById("upload_dialog").innerHTML = "<p>" + e.result + "</p>"),
                                              t.dialog("option", {
                                                  buttons: [
                                                      {
                                                          text: lang.close,
                                                          click: function () {
                                                              t.remove();
                                                          },
                                                      },
                                                  ],
                                              }),
                                              bids.set_status(e))
                                            : alert(e.errormsg);
                                    }
                                );
                            },
                        },
                    ],
                    open: function () {
                        $(".ui-dialog").css("z-index", 1003),
                            $(".ui-widget-overlay").click(function () {
                                t.remove();
                            });
                    },
                    modal: !0,
                    width: $("body").width() > 800 ? 800 : "95%",
                    maxHeight: "90%",
                });
        },
        description_dialog: function (e) {
            $("#description_dialog").remove();
            var t = document.getElementById("description_" + e).innerHTML,
                a = $('<div id="description_dialog" title="' + lang.partnersDescr + '" style="display:none" onclick="bids.textarea_dialog(' + e + ')">' + t + "</div>")
                    .appendTo("body")
                    .dialog({
                        autoOpen: !0,
                        open: function () {
                            $(".ui-widget-overlay").click(function () {
                                a.remove();
                            });
                        },
                        modal: !0,
                        width: $("body").width() > 800 ? 800 : "95%",
                        maxHeight: "90%",
                    });
        },
        textarea_dialog: function (e) {
            var t = $("#description_dialog").text(),
                a = $("<textarea id='textarea_dialog' />");
            a.val(t), $("#description_dialog").hide(), $("#description_dialog").after('<button id="save_description" onclick="bids.dialog_save(' + e + ')">' + lang.save + "</button>"), $("#description_dialog").after(a), a.focus();
        },
        dialog_save: function (e) {
            var t = $("#textarea_dialog").val();

            $.ajax({
                type: "POST",
                url: my_ajax_object.ajax_url,
                dataType : 'html',
                data: {
                    action: 'change_description', 
                    description: t, 
                    bid_id: e, 
                },
                success: function(response) {
                    console.log(response);
                    $("#textarea_dialog").remove(); 
                    $("#save_description").remove(); 
                    $("#description_" + e).text(t); 
                    $("#item_control_" + e + " a").text(t); 
                    $("#description_dialog").text(t); 
                    $("#description_dialog").show();

                }
            });

            
        },
        show_image: function (e) {
            var t = $(".decline_img[data-bid_id=" + e + "]").attr("data-reason"),
                a = $(".decline_img[data-bid_id=" + e + "]").attr("data-image");
            (t = void 0 !== t ? "<div>" + t + "</div>" : ""), (a = void 0 !== a ? '<img src="' + a + '">' : "");
            $("<div>" + t + a + "</div>")
                .appendTo("body")
                .dialog({
                    close: function () {
                        $(this).dialog("close"), $(this).remove();
                    },
                    title: lang.rejectionReason,
                    modal: !0,
                    width: $("body").outerWidth() > 900 ? 900 : $("body").outerWidth() - 30,
                });
        },
    }),
    (orders = {
        buy: function () {
            var e = $("#buy_dialog");
            if (!document.getElementById("conditions").checked) return alert(lang.agreement), !1;
            var t = e.find("form").serializeObject(),
                a = e.find("#buy_button");
            (a[0].disabled = !0),
                (document.getElementById("creating_order").style.display = "block"),
                a.prop("disabled", !0),
                $.post("/req/buy.php", t, function (t) {
                    (document.getElementById("creating_order").style.display = "none"),
                        0 == t.code
                            ? parseInt(t.is_html)
                                ? $(t.data).appendTo("body").submit()
                                : (location.href = t.data)
                            : (2 == t.code && ($("#step1").removeClass("collapsed"), $("#step2").addClass("collapsed")),
                              a.prop("disabled", !0),
                              1 == t.code && (alert(t.errormsg), a.prop("disabled", !1)),
                              a.removeClass("ui-button-disabled"),
                              a.removeClass("ui-state-disabled"),
                              3 == t.code &&
                                  (e.dialog("close"),
                                  sessionStorage.setItem("after_login_href", location.pathname),
                                  $("#login_form .form-line:first").addClass("line-alert"),
                                  $(".login-error").text(t.errormsg),
                                  $(".login-error").show(),
                                  $(".login-open").click()));
                });
        },
        basket: function (e) {
            $("body").append('<img id="buy-dialog-loader" src="/img/buy-dialog-loader.gif">'),
                $.post(
                    "/req/deferred.php",
                    { section: "buy_dialog", item_id: e, secret: itemSalt },
                    function (t) {
                        if (0 == t.code) {
                            0 === currentCurrency && (currentCurrency = t.currency),
                                $("body").append(t.content),
                                $("#buy_dialog form")[0].reset(),
                                $(".step_in_process").show(),
                                $(".step_1").removeClass("done"),
                                $(".step_2").removeClass("active"),
                                $(".step_done").hide(),
                                $("#creating_order").hide(),
                                (document.getElementById("item_id").value = e),
                                (document.getElementById("item_price").value = t.price),
                                (document.getElementById("basket_qty").value = 1),
                                $("#basket_qty").spinner({
                                    min: 1,
                                    max: t.max_qty,
                                    change: function () {
                                        orders.price();
                                    },
                                    stop: function () {
                                        orders.price();
                                    },
                                }),
                                $("#payment_system").change(function () {
                                    orders.price();
                                }),
                                $("#item_partners").on("click", "li:not(.preloader)", function () {
                                    $("#item_partners li").removeClass("active"),
                                        $(this).addClass("active"),
                                        orders.price(),
                                        $("#step1").addClass("collapsed"),
                                        $(".step_in_process").hide(),
                                        $(".step_done").show(),
                                        $(".step_1").addClass("done"),
                                        $(".step_2").addClass("active"),
                                        $("#step2").removeClass("collapsed");
                                }),
                                $("#step1 .card-header").click(function () {
                                    $("#step1").removeClass("collapsed"), $(".step_done").hide(), $(".step_in_process").show(), $(".step_1").removeClass("done"), $(".step_2").removeClass("active"), $("#step2").addClass("collapsed");
                                }),
                                (document.getElementById("item_name").innerHTML = t.name);
                            var a = $("#buy_dialog").dialog({
                                autoOpen: !0,
                                modal: !0,
                                dialogClass: "buy_dialog",
                                resizable: !1,
                                width: "auto",
                                buttons: [],
                                open: function () {
                                    $(".ui-widget-overlay").click(function () {
                                        a.remove();
                                    });
                                },
                                close: function () {
                                    $("#buy_dialog").remove();
                                },
                            });
                        } else alert("undefined" == t.errormsg || "" == t.errormsg ? lang.productNotAvailable : t.errormsg);
                    },
                    "json"
                )
                    .fail(function (e) {
                        console.log(e.status), 200 !== e.status && $("body").html(respErr);
                    })
                    .done(function () {
                        $("body").css({ opacity: 1 }), $("#buy-dialog-loader").remove();
                    });
        },
        // subscribers_dialog: function (e, t) {
        //     var a = "<br />" + lang.subscribeLabel + "<br /><b>" + t + "</b><br /><br />",
        //         i = $(
        //             '<div id="subscribers_dialog" title="' +
        //                 lang.subscribeTitle +
        //                 '" style="display:none">' +
        //                 a +
        //                 '<input type="text" id="subscribe_email" autocomplete="off" placeholder="' +
        //                 lang.enterEmail +
        //                 '"><span id="valid"></span><br /><br /><span class="red">' +
        //                 lang.confirmSubscribtion +
        //                 '</span><br /><br /><button id="save_subscribe" onclick="orders.subscribe_save(' +
        //                 e +
        //                 ')">' +
        //                 lang.subscribe +
        //                 "</button></div>"
        //         )
        //             .appendTo("body")
        //             .dialog({
        //                 autoOpen: !0,
        //                 open: function () {
        //                     $(".ui-widget-overlay").click(function () {
        //                         i.remove();
        //                     });
        //                 },
        //                 modal: !0,
        //                 width: $("body").width() > 800 ? 800 : "95%",
        //                 maxHeight: "90%",
        //             });
        // },
        // subscribe_save: function (e) {
        //     var t = $("#subscribe_email").val();
        //     if ("" != t) {
        //         /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i.test(t)
        //             ? $.post("/req/subscribe.php", { do: "subscribe", item_id: e, email: t }, function (e) {
        //                   0 == e.code ? ($(".ui-dialog").fadeOut(), $("#subscribers_dialog").html(e.message), $(".ui-dialog").fadeIn()) : (alert(e.errormsg), (i.innerHTML = e.errormsg));
        //               })
        //             : ($(this).css({ border: "1px solid #ff0000" }), $("#valid").text(lang.invalidEmail));
        //     } else $(this).css({ border: "1px solid #ff0000" }), $("#valid").text(lang.errorEmptyEmail);
        // },
        invalid: function (e, t, a, i) {
            var o = t.parentNode;
            (o.innerHTML = '<img src="/img/ajax-load.gif" alt="">'),
                setTimeout(function () {
                    $.post("/req/orders.php", { do: "invalid", account_id: e, type: i, order_id: a }, function (t) {
                        0 == t.code
                            ? ($(".replace_table_" + e).replaceWith("<p>" + lang.processed + "</p>"),
                              $("#ch" + e)
                                  .closest("td")
                                  .html(""))
                            : (alert(t.errormsg), (o.innerHTML = t.errormsg));
                    });
                }, 500);
        },
        massInvalid: function (e, t) {
            var a = $("table.table_accounts td input[type=checkbox]:checked"),
                i = [];
            a.each(function (e, t) {
                var a = t.dataset.id;
                $(t)
                    .parents("tr")
                    .find("td")
                    .eq(2)
                    .html('<img class="replace_table_' + a + '" src="/img/ajax-load.gif" alt="">'),
                    i.push(a);
            }),
                i &&
                    $.post("/req/orders.php", { do: "massInvalid", account_ids: i, type: t, order_id: e }, function (e) {
                        1 == e.code && alert(e.errormsg),
                            $.each(e.accounts, function (e, t) {
                                $(".replace_table_" + t.id).replaceWith("<p>" + t.message + "</p>"),
                                    $("#ch" + t.id)
                                        .closest("td")
                                        .html("");
                            });
                    });
        },
        changeCheckState: function (e) {
            $(e).parents("table.table_accounts").find("tr input[type=checkbox]").prop("checked", e.checked);
        },
        order_dialog: function (e) {
            $.post("/req/orders.php", { do: "order_dialog", order_id: e }, function (t) {
                if (1 != parseInt(t.code)) {
                    var a =
                            '<input type="text" name="search" style="width:50%" onkeyup="orders.order_dialog_filter(this)" autocomplete="off" placeholder="' +
                            lang.searchAccounts +
                            '"><table style="float:right" border="1"><tr><td colspan="2"><b>' +
                            lang.replace +
                            '</b></td><td colspan="2"><b>' +
                            lang.moneyBack +
                            '</b></td></tr><tr><td><a href="javascript:void(0)" onclick="orders.massInvalid(' +
                            e +
                            ', 1)"><img style="max-width:50px;" src="/img/with.png"></a></td><td><a href="javascript:void(0)" onclick="orders.massInvalid(' +
                            e +
                            ', 2)"><img style="max-width:50px;" src="/img/without.png"></a></td><td><a href="javascript:void(0)" onclick="orders.massInvalid(' +
                            e +
                            ', 3)"><img style="max-width:50px;" src="/img/with.png"></a></td><td><a href="javascript:void(0)" onclick="orders.massInvalid(' +
                            e +
                            ', 4)"><img style="max-width:50px;" src="/img/without.png"></a></td></tr></table>',
                        i = 0;
                    for (var o in t.accounts) {
                        for (var n in ((a +=
                            '<p class="bold">' +
                            lang.orderNo +
                            o +
                            '</p><table class="list zebra ac table_accounts"><tr><th><input type="checkbox" onchange="orders.changeCheckState(this)" id="check_all_accounts_' +
                            i +
                            '"><label for="check_all_accounts_' +
                            i +
                            '"></label></th><th>' +
                            lang.account +
                            "</th><th>" +
                            lang.actions +
                            "</th></tr>"),
                        t.accounts[o])) {
                            i++;
                            var s = t.accounts[o][n],
                                r = parseInt(s.invalid)
                                    ? lang.invalid
                                    : '<table border="1" class="replace_table_' +
                                      s.id +
                                      '"><tr><td colspan="2"><b>' +
                                      lang.replace +
                                      '</b></td><td colspan="2"><b>' +
                                      lang.moneyBack +
                                      '</b></td></tr><tr><td><a href="javascript:void(0)" onclick="orders.invalid(' +
                                      s.id +
                                      ", this, " +
                                      e +
                                      ', 1)"><img style="max-width:50px;" src="/img/with.png"></a></td><td><a href="javascript:void(0)" onclick="orders.invalid(' +
                                      s.id +
                                      ", this, " +
                                      e +
                                      ', 2)"><img style="max-width:50px;" src="/img/without.png"></a></td><td><a href="javascript:void(0)" onclick="orders.invalid(' +
                                      s.id +
                                      ", this, " +
                                      e +
                                      ', 3)"><img style="max-width:50px;" src="/img/with.png"></a></td><td><a href="javascript:void(0)" onclick="orders.invalid(' +
                                      s.id +
                                      ", this, " +
                                      e +
                                      ', 4)"><img style="max-width:50px;" src="/img/without.png"></a></td></tr></table>';
                            a +=
                                "<tr" +
                                (parseInt(s.invalid) ? ' style="background-color:#f99"' : "") +
                                "><td>" +
                                (parseInt(s.invalid) ? "" : '<input id="ch' + s.id + '" data-id="' + s.id + '" type="checkbox"><label for="ch' + s.id + '"></label>') +
                                '</td><td id="a' +
                                s.id +
                                '" data-account="' +
                                s.data +
                                '">' +
                                s.data +
                                (parseInt(s.replacement) ? " (" + lang.replace + ")" : "") +
                                "</td><td>" +
                                r +
                                "</td></tr>";
                        }
                        a += "</table>";
                    }
                    $('<div id="order_view_dialog" title="' + lang.viewOrderNo + e + '">' + a + "</div>")
                        .appendTo("body")
                        .dialog({
                            modal: !0,
                            width: $("body").width() > 700 ? 700 : "80%",
                            open: function () {
                                document.body.style.overflow = "hidden";
                            },
                            close: function () {
                                (document.body.style.overflow = "inherit"), $("#order_view_dialog").remove();
                            },
                        });
                } else alert(t.errormsg);
            });
        },
        order_dialog_filter: function (e) {
            var t = document.querySelectorAll("[data-account]");
            for (var a in t) t[a].parentNode.style.display = t[a].dataset.account.indexOf(e.value) > -1 ? "table-row" : "none";
        },
        price: function () {
            var t = lang.id,
                a = "",
                i = "",
                o = "0",
                n = "0",
                s = (lang.id, currentCurrency),
                r = parseFloat($("#item_partners li.active").attr("data-price"));
            e = r.toFixed(3);
            var d = e * parseInt(document.getElementById("basket_qty").value),
                l = 0,
                c = document.getElementById("payment_system"),
                p = parseInt(c.options[c.selectedIndex].value);
            (l = 1 == s ? (d > 0.01 ? d.toFixed(2) + " " + lang.rub : d.toFixed(3) + " " + lang.rub) : d > 0.01 ? "$" + d.toFixed(2) : "$" + d.toFixed(3)),
                _min_payment_text[p] && (o = p),
                _min_payment_text[o][1] && (n = o),
                c.options[c.selectedIndex].value == psQiwi ? addPhoneInput() : removePhoneInput();
            var m = parseFloat(c.options[c.selectedIndex].getAttribute("data_min_payment_" + currentCurrency)),
                u = d.toFixed(2),
                g = m + lang.rub;
            if (
                ($.each(c, function (e, t) {
                    var a = $(t).attr("data_payment_system_label"),
                        i = +parseFloat($(t).attr("data_min_payment_" + s)).toFixed(2),
                        o = +parseFloat($(t).attr("data_max_payment_" + s)).toFixed(2);
                    if (u < i || (o > 0 && u > o)) {
                        if (u < i) var n = 2 == s ? lang.min_payment + " $" + i : lang.min_payment + i + " " + lang.rub;
                        else n = 2 == s ? lang.max_payment + " $" + o : lang.max_payment + o + " " + lang.rub;
                        $(t).prop("disabled", !0), $(t).text(a + " (" + n + ")"), $(t).val() == $("select[name=payment_system]").val() && $("select[name=payment_system]").val(0);
                    } else $(t).prop("disabled", !1), $(t).text(a);
                }),
                2 == s && (g = "$" + m),
                _min_payment_text[o][0])
            )
                for (var h = 0; h < _min_payment_text[o][0].length; h++) _min_payment_text[o][0][h]["message_" + t].length > 0 && (i += '<p class="red">(!) - ' + _min_payment_text[o][0][h]["message_" + t].replace("[SUM]", g) + "</p>");
            if (_min_payment_text[n][1] && !_min_payment_text[o])
                for (h = 0; h < _min_payment_text[n][1].length; h++) _min_payment_text[n][1][h]["message_" + t].length > 0 && '<p class="red">(!) - ' + _min_payment_text[n][1][h]["message_" + t].replace("[SUM]", g) + "</p>";
            var f = $("#buy_button");
            $(".buy_button").prop("disabled", !1),
                $(".buy_button").removeClass("ui-button-disabled"),
                $(".buy_button").removeClass("ui-state-disabled"),
                u < m ? ((a += '<p class="red">(!) - min sum - ' + g + "</p>"), (a += i), f.prop("disabled", !0), $(".buy_button").addClass("ui-button-disabled"), $(".buy_button").addClass("ui-state-disabled")) : f.prop("disabled", !1);
            var _ = $("#buy_dialog input[name=email]");
            _ && "" == _.val() && ((a += '<p class="red">(!) - ' + lang.emailRequired + "</p>"), $(".buy_button").prop("disabled", !0), $(".buy_button").addClass("ui-button-disabled"), $(".buy_button").addClass("ui-state-disabled")),
                document.getElementById("conditions").checked ||
                    0 != $(".buy_button").is(":disabled") ||
                    ($(".buy_button").prop("disabled", !0), $(".buy_button").addClass("ui-button-disabled"), $(".buy_button").addClass("ui-state-disabled"));
            var v = $("#item_partners li.active").attr("data-id"),
                b = parseInt($("#item_partners li.active").attr("data-qty"));
            b < parseInt($("#basket_qty").val()) &&
                ((a += '<p class="red">(!) - ' + lang.partnersQty.replace("qty", b) + "</p>"), $(".buy_button").prop("disabled", !0), $(".buy_button").addClass("ui-button-disabled"), $(".buy_button").addClass("ui-state-disabled")),
                $("#item_partners_id").val(v),
                v || 0 != $(".buy_button").is(":disabled") || ($(".buy_button").prop("disabled", !0), $(".buy_button").addClass("ui-button-disabled"), $(".buy_button").addClass("ui-state-disabled"));
            var y = $(".phone-input-number");
            y.prop("required") &&
                ("" == y.val() || y.val().length < 11) &&
                ((a += '<p class="red">(!) - ' + ("" == y.val() ? lang.phoneRequired : lang.phoneLimit) + "</p>"),
                $(".buy_button").prop("disabled", !0),
                $(".buy_button").addClass("ui-button-disabled"),
                $(".buy_button").addClass("ui-state-disabled"));
            a.length > 0 ? (document.getElementById("p_alert").innerHTML = a) : (document.getElementById("p_alert").innerHTML = ""), (document.getElementById("price").innerHTML = l);
        },
        send_order_email: function (e) {
            $.post("/req/orders.php", { do: "send_order_email", order_id: e }, function (e) {
                _notify({ icon: "notify", message: lang.messageSended });
            });
        },
        wallets_dialog: function (e) {
            var t = document.getElementById("wallets_" + e).innerHTML,
                a = $('<div id="wallets_dialog" title="' + lang.walletsList + '" style="padding-top:10px; display:none">' + t + "</div>")
                    .appendTo("body")
                    .dialog({
                        autoOpen: !0,
                        open: function () {
                            $(".ui-widget-overlay").click(function () {
                                a.remove();
                            });
                        },
                        modal: !0,
                        width: $("body").width() > 800 ? 800 : "95%",
                        maxHeight: "90%",
                    });
        },
        partner_pay: function (e, t, a) {
            var i = document.getElementById("wallets_" + a + e).innerHTML,
                o = document.getElementById("payment_sum_" + a + e).innerHTML,
                n = "/ru/admin/payments_partners/pay/" + t;
            "referral" == a && (n = "/ru/admin/payments_referrals/pay/" + t);
            var s = $(
                '<div id="partner_pay_dialog" title="' +
                    lang.partnerPay +
                    '" style="display:none"><form action="' +
                    n +
                    '" method="post" enctype="multipart/form-data"><div class="partner_pay_popup_block wallets"><span>' +
                    lang.walletsList +
                    "</span><span>" +
                    i +
                    '</span></div><div class="partner_pay_popup_block sum"><span>' +
                    lang.amountPayment +
                    "</span><span>" +
                    o +
                    '</span></div><div class="partner_pay_popup_block form"><span>' +
                    lang.commentPayment +
                    '</span><span><div class="flex-container"><div class="pay_comment_first"><textarea name="pay_comment" placeholder="Комментарий на почту партнеру" required></textarea></div><div class="pay_comment_first"><textarea name="pay_comment_tp" placeholder="Комментарий для ТП" required></textarea></div></div><div class="partner_pay_popup_block form"><span>Скриншот выплаты</span><span><input type="submit" value="' +
                    lang.pay +
                    '"></span></div></form></div>'
            )
                .appendTo("body")
                .dialog({
                    autoOpen: !0,
                    open: function () {
                        $(".ui-widget-overlay").click(function () {
                            s.remove();
                        });
                    },
                    modal: !0,
                    width: $("body").width() > 800 ? 800 : "95%",
                    maxHeight: "90%",
                    close: function () {
                        s.remove();
                    },
                });
        },
    }),
    (scrollToBottom = function (e, t = !1) {
        !1 === t && (e.is(":animated") && e.stop(), e.scrollTo("max", 800, { interrupt: !0, easing: "easeOutCubic" }));
    }),
    (setCounter = function (e, t) {
        parseInt(t) > 0 ? (e.dataset.counter = t) : e.removeAttribute("data-counter");
    }),
    (moveCaretToEnd = function (e) {
        var t = e.value.length;
        e.focus(), (e.selectionStart = t), (e.selectionEnd = t);
    }),
    (tickets = {
        data: { level: 0, check_access: 0, tca: 0, secret: "", session_id: 0, sessions: {}, subjects: {}, uid: 0 },
        init: function () {
            var e = function () {
                $.post("/req/tickets.php?do=im_online", { ticket_id: tickets.data.session_id });
                try {
                    tickets.data.session_id &&
                        $.post({
                            url: ["/req/tickets.php?do=get_msg"],
                            type: "post",
                            data: { ticket_id: tickets.data.session_id, secret: tickets.data.secret },
                            success: function (e) {
                                if (void 0 !== e) {
                                    var t = e[tickets.data.session_id][tickets.data.session_id];
                                    void 0 !== t &&
                                        t.length &&
                                        $.each(t, function (e, t) {
                                            $("#m" + t.id).length || tickets.onmessage(JSON.stringify(t), !0);
                                        });
                                }
                            },
                            error: function (e, t) {
                                console.log(e), console.log(t);
                            },
                            complete: function () {},
                        });
                } catch (e) {
                    console.log(e);
                }
            };
            if ((setInterval(e, 1e4), !document.location.hash && tickets.data.session_id && (document.location.hash = tickets.data.session_id), document.location.hash)) {
                var t = parseInt(document.location.hash.substr(1));
                this.session(t);
            } else e();
        },
        mark_read: function (e, t) {
            $.post("/req/tickets.php", { do: "mark_read", ticket_id: e, id: t }, function (a) {
                if (
                    ($("#m" + t)
                        .removeAttr("onmouseover")
                        .removeClass("unread"),
                    parseInt(this.data.level) > 2)
                ) {
                    var i = 0;
                    for (var o in tickets.data.sessions[e].messages) {
                        var n = tickets.data.sessions[e].messages[o];
                        parseInt(n.id) == t && (n.unread = 0), parseInt(n.unread) && ++i;
                    }
                    (tickets.data.sessions[e].unread = i), tickets.update_counter(e);
                }
            });
        },
        message_html: function (e) {
            var t = e.name;
            e.name || (t = parseInt(this.data.uid) ? lang.client : lang.you);
            var a = (parseInt(e.unread) && this.data.level) || (!parseInt(e.client_read) && !this.data.level) ? ' onmouseover="tickets.mark_read(' + e.ticket_id + ", " + e.id + ')"' : "",
                i = "message";
            ((parseInt(e.unread) && this.data.level) || (!parseInt(e.client_read) && !this.data.level)) && (i += " unread"), this.data.level > 2 && !parseInt(e.client_read) && (i += " client_unread");
            var o = "";
            if (void 0 !== e.files) {
                for (var n in ((o += '<div class="cb"></div><div class="attachments">'), e.files)) o += '<div><a href="' + e.files[n].file + '" target="_blank">' + e.files[n].filename + "</a></div>";
                o += "</div>";
            }
            var s = "",
                r = "";
            this.data.level > 1 && ((s = ' ondblclick="tickets.textarea_dialog(' + e.id + ')"'), (r = "<span class='ip'>IP: " + e.ip + "</span>"));
            var d = "",
                l =
                    "M2.94964 0H13.3844C14.6431 0 15.667 1.0239 15.667 2.28262V9.45654C15.667 10.7152 14.6431 11.7391 13.3844 11.7391H6.34548L3.17983 14.9048C3.11787 14.9667 3.03441 15 2.9496 15C2.90788 15 2.86483 14.9922 2.82503 14.9752C2.70308 14.925 2.62351 14.8056 2.62351 14.6739V11.7156C1.51872 11.5572 0.666992 10.6043 0.666992 9.45651V2.28262C0.667023 1.0239 1.69095 0 2.94964 0ZM8.16702 9.7826C8.70638 9.7826 9.14528 9.3437 9.14528 8.80434C9.14528 8.26498 8.70638 7.82608 8.16702 7.82608C7.62767 7.82608 7.18876 8.26498 7.18876 8.80434C7.18876 9.3437 7.62767 9.7826 8.16702 9.7826ZM6.86527 3.00522L7.14636 6.22825C7.18354 6.75587 7.63289 7.17389 8.16702 7.17389C8.70702 7.17389 9.15571 6.75583 9.19288 6.22238L9.46811 3.00913C9.48767 2.73522 9.39376 2.46653 9.20985 2.26892C9.02462 2.07 8.76376 1.95652 8.49309 1.95652H7.84093C7.57029 1.95652 7.3094 2.07 7.1242 2.26892C6.94029 2.46717 6.8457 2.73522 6.86527 3.00522Z";
            return (
                e.hasComplaint > 0
                    ? (d = '<svg class="complaint bad-complaint" width="26" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">\n<path d="' + l + '"/>\n</svg>')
                    : (1 != this.data.level && 5 != this.data.level && 0 != this.data.level) ||
                      (2 != e.level && 3 != e.level && 4 != e.level) ||
                      (d = '<svg class="complaint" width="26" height="16" viewBox="0 0 16 16" fill="#A4A4A4" xmlns="http://www.w3.org/2000/svg">\n<path onclick="tickets.complaint(' + e.id + ')" d="' + l + '"/>\n</svg>'),
                '<div id="m' +
                    e.id +
                    '" class="' +
                    i +
                    '"' +
                    a +
                    '><div><span class="author">' +
                    t +
                    "</span>" +
                    d +
                    '<span class="time">' +
                    makedate(e.time, 1) +
                    "; </span>" +
                    r +
                    '<div class="m"><span id="mess' +
                    e.id +
                    '"' +
                    s +
                    ">" +
                    e.message
                        .toString()
                        .replace(/&amp;/g, "&")
                        .replace(/&lt;/g, "<")
                        .replace(/&gt;/g, ">")
                        .replace(/&quot;/g, '"')
                        .replace(/&#039;/g, "'")
                        .replace(/\n/g, "<br>") +
                    "</span>" +
                    o +
                    "</div></div></div>"
            );
        },
        complaint: function (e) {
            var t = $(
                '<div id="complaint_dialog" title="Подать жалобу на ответ оператора" style="display:none">' +
                    ('<form><label>Укажите причину жалобы:</label><br><textarea id="complaint_body_area" placeholder="Напишите текст жалобы"></textarea><button id="complaint_button" class="complaint-btn" onclick="tickets.sendComplaint(' +
                        e +
                        ', this)">Отправить жалобу</button><div class="time-terms">Срок рассмотрения жалобы - 48 часов. Пожалуйста дождитесь ответа, и не отсылайте жалобы повторно</div></form>') +
                    "</div>"
            )
                .appendTo("body")
                .dialog({
                    autoOpen: !0,
                    open: function () {
                        $(".ui-widget-overlay").click(function () {
                            t.remove();
                        });
                    },
                    modal: !0,
                    width: "402px",
                    maxHeight: "40%",
                });
        },
        sendComplaint: function (e, t) {
            var a = $("#complaint_body_area").val();
            (t.disabled = !0),
                $.post("/req/tickets.php", { do: "complaint", id: e, body: a }, function (a) {
                    (t.disabled = !1),
                        0 == a.code
                            ? ($("#m" + e + " .complaint")
                                  .addClass("bad-complaint")
                                  .find("path")
                                  .attr("onclick", null),
                              $("#complaint_dialog").dialog("destroy"))
                            : a.errormsg && alert(a.errormsg);
                });
        },
        textarea_dialog: function (e) {
            var t = $("#mess" + e).html();
            t = t.replace(/<br>/g, "\n");
            var a = $("<textarea style='width:700px;' id='textarea_message_dialog' />");
            a.val(t), $("#mess" + e).hide(), $("#mess" + e).after('<br /><button id="save_description" onclick="tickets.dialog_save(' + e + ')">' + lang.save + "</button>"), $("#mess" + e).after(a), a.focus();
        },
        dialog_save: function (e) {
            var t = $("#textarea_message_dialog").val();
            $.post("/req/tickets.php", { do: "change_message", message_id: e, description: t }, function (a) {
                0 == a.code ? ($("#textarea_message_dialog").remove(), $("#save_description").remove(), $("#mess" + e).html(t.replace(/\n/g, "<br>")), $("#mess" + e).show()) : (alert(a.errormsg), (i.innerHTML = a.errormsg));
            });
        },
        new_ticket: function (e) {
            return (
                form.send(e, function (e) {
                    "string" == typeof e.ticket_link && e.ticket_link.length > 0 ? (location.href = e.ticket_link) : $("#ticket_result").html(e.message).show();
                }),
                !1
            );
        },
        onmessage: function (e, t = !1) {
            switch ((e = JSON.parse(e)).act) {
                case "message":
                    tickets.data.session_id == e.ticket_id && ($(tickets.message_html(e)).appendTo("#ticket_messages"), scrollToBottom($("#ticket_messages"), t)),
                        void 0 !== tickets.data.sessions[e.ticket_id] && (((a = tickets.data.sessions[e.ticket_id].messages)[a.length] = e), tickets.update_counter(e.ticket_id));
                    break;
                case "client_read":
                    if (void 0 !== tickets.data.sessions[e.ticket_id]) {
                        var a = tickets.data.sessions[e.ticket_id].messages;
                        for (var i in (console.log(JSON.stringify(e)), a))
                            if ((console.log(JSON.stringify(a[i])), parseInt(a[i].id) == parseInt(e.id))) {
                                (a[i].client_read = 1), console.log("read"), (tickets.data.sessions[e.ticket_id].messages = a);
                                break;
                            }
                        $("#m" + e.id).removeClass("client_unread");
                    }
            }
        },
        reorderSessions: function () {
            Array.prototype.sort
                .call($("#tickets_sessions > a"), function (e, t) {
                    var a = parseInt(e.dataset.unread),
                        i = parseInt(t.dataset.unread),
                        o = parseInt(e.dataset.time),
                        n = parseInt(t.dataset.time);
                    return a && !i ? -1 : !a && i ? 1 : o == n ? 0 : o > n ? -1 : 1;
                })
                .appendTo("#tickets_sessions");
        },
        send_message: function (e) {
            $('button[type="submit"]', e).prop("disabled", !0);
            var t = this.data.secret;
            this.data.secret || this.data.check_access || (t = this.data.sessions[this.data.session_id].secret);
            var a = $.extend($(e).serializeObject(), { do: "send_message", ticket_id: this.data.session_id, secret: t });
            return (
                $.post("/req/tickets.php", a, function (t) {
                    0 == parseInt(t.code)
                        ? ($('button[type="submit"]', e).prop("disabled", !1), (document.getElementById("message").value = ""), (document.getElementById("attachments").innerHTML = ""), moveCaretToEnd(document.getElementById("message")))
                        : alert(t.errormsg);
                }).success(function () {
                    window.location.reload();
                }),
                !1
            );
        },
        sessions: function () {
            if (document.getElementById("tickets_sessions")) {
                var e = "",
                    t = document.getElementById("search").value.toLowerCase();
                for (var a in this.data.sessions) {
                    var i = this.data.sessions[a];
                    if (!t || i.order_id == t || -1 != i.email.toLowerCase().indexOf(t)) {
                        for (var o in ((i.unread = 0), this.data.sessions[a].messages)) parseInt(this.data.sessions[a].messages[o].unread) && ++i.unread;
                        var n = void 0 !== this.data.subjects[i.subject_id] ? this.data.subjects[i.subject_id].image : "",
                            s = i.icon ? '<img class="order_img" src="/img/tickets_icons/' + i.icon + '" />' : "";
                        (s = '<span id="ticket_session_image' + i.id + '">' + s + "</span>"),
                            (e +=
                                '<a data-unread="' +
                                i.unread +
                                '" data-time="' +
                                i.time +
                                '" href="javascript:void(0)" onclick="tickets.session(' +
                                i.id +
                                ')" id="ticket_session' +
                                i.id +
                                '" style="background-image:url(' +
                                n +
                                ')">' +
                                s +
                                '<span class="order">' +
                                i.email +
                                '</span><div class="counters"><span class="new-m"' +
                                (i.unread ? "" : ' style="display:none"') +
                                ">" +
                                i.unread +
                                "</span></div></a>");
                    }
                }
                e || (e = "<p>" + lang.notFound + "</p>"), (document.getElementById("tickets_sessions").innerHTML = e), tickets.reorderSessions();
            }
        },
        session: function (e) {
            if (0 !== parseInt(e)) {
                (document.location.hash = "#" + e), (this.data.session_id = e);
                var t = this.data.tca,
                    a = this.data.sessions[e],
                    i =
                        1 == a.closed
                            ? lang.closed + ' (<a href="#" onclick="tickets.session_status(' + e + ', 0)">' + lang.open + "</a>)"
                            : lang.opened + ' (<a href="#" onclick="tickets.session_status(' + e + ', 1)">' + lang.close + "</a>)";
                0 === t && (i = 1 == a.closed ? lang.closed : lang.opened), $("#tickets_sessions > a").removeClass("active"), $("#ticket_session" + e).addClass("active");
                var o = '<div class="frame"><table class="form">',
                    n = "";
                for (var s in (0 !== t &&
                    (n =
                        ' <a href="javascript:void(0)" onclick="admin_tickets.change_order_id(' +
                        e +
                        ')">' +
                        lang.edit +
                        "</a> " +
                        (parseInt(a.order_id)
                            ? '<a href="javascript:void(0)" onclick="orders.order_dialog(' +
                              a.order_id +
                              ')">' +
                              lang.viewOrder +
                              '</a> <a href="javascript:void(0)" onclick="orders.send_order_email(' +
                              a.order_id +
                              ')">' +
                              lang.sendOrderEmail +
                              "</a>"
                            : "")),
                (o += "<tr><td>" + lang.status + "</td><td id='status" + e + "'>" + i + "</td><tr><tr><td>" + lang.requestDateLabel + "</td><td>" + makedate(a.time, 1) + "</td></tr>"),
                void 0 !== this.data.subjects[a.subject_id] && (o += "<tr><td>" + lang.subject + "</td><td>" + this.data.subjects[a.subject_id].name + "</td></tr>"),
                this.data.level >= 2 &&
                    5 != this.data.level &&
                    ((o += '<tr><td>Email:</td><td><a href="mailto:' + a.email + '" target="_blank">' + a.email + "</a></td></tr>"),
                    a.order_id_client && (o += "<tr><td>" + lang.orderNumLabel + "</td><td>" + a.order_id_client + "</td></tr>"),
                    (o += "<tr><td>" + lang.orderNum + '</td><td class="o_controls">' + a.order_id + n + "</td></tr>"),
                    (o += "<tr><td>" + lang.icons + '</td><td class="t_icons" data-ticket_id="' + e + '">' + this.data.icons + "</td></tr>"),
                    parseInt(a.order_id) &&
                        (o +=
                            "<tr><td>" +
                            lang.ticketForPartner +
                            '</td><td id="partner' +
                            e +
                            '">' +
                            (parseInt(a.bid_id) ? lang.published : '<a href="javascript:void(0)" onclick="admin_tickets.partner(' + e + ')">' + lang.publish + "</a>") +
                            "</td></tr>") &&
                        (o += '<tr><td>Комментарий: <a href="#" onclick="tpanel_add_comment(' + e + ');">(Доб/изм)</a></td><td><span id="tpanel_comment">' + a.comment + "</span></td>")),
                a.fields))
                    void 0 !== this.data.subjects[a.subject_id].fields[s] && (o += "<tr><td>" + this.data.subjects[a.subject_id].fields[s] + ":</td><td>" + a.fields[s].toString().replace("\n", "<br>") + "</td></tr>");
                (o += '</table></div><div id="ticket_messages"><p class="ac"><img src="/img/ajax-load.gif" alt=""></p></div>'), (document.getElementById("ticket_body").innerHTML = o), (o = "");
                var r = 0;
                for (var d in a.messages) {
                    (o += tickets.message_html(a.messages[d])), (2 != (p = a.messages[d]).level && 3 != p.level && 4 != p.level) || p.hasComplaint || (r = p.id);
                }
                if (0 != a.closed && !a.mark && (1 == a.level || 5 == a.level)) {
                    for (
                        var l = {
                                1: '<svg width="31" height="31" viewBox="0 0 31 31" fill="#A4A4A4" xmlns="http://www.w3.org/2000/svg">\n<path d="M21.4634 10.3744C20.0342 10.3744 18.876 11.5326 18.876 12.9618C18.876 14.3911 20.0342 15.5493 21.4634 15.5493C22.8927 15.5493 24.0509 14.3911 24.0509 12.9618C24.0262 11.5326 22.8927 10.3744 21.4634 10.3744ZM12.1486 12.9618C12.1486 11.5326 10.9905 10.3744 9.56121 10.3744C8.13196 10.3744 6.97377 11.5326 6.97377 12.9618C6.97377 14.3911 8.13196 15.5493 9.56121 15.5493C10.9905 15.5493 12.1486 14.3911 12.1486 12.9618ZM15.5 0C6.94913 0 0 6.94913 0 15.5C0 24.0755 6.94913 31 15.5 31C24.0509 31 31 24.0509 31 15.5C31 6.94913 24.0755 0 15.5 0ZM15.5 28.5604C8.27981 28.5604 2.46423 22.7202 2.43959 15.5C2.43959 8.27981 8.27981 2.46423 15.5 2.43959C22.7202 2.46423 28.5358 8.27981 28.5604 15.5C28.5358 22.7202 22.7202 28.5604 15.5 28.5604ZM15.5 19.3196C13.3068 19.3196 11.089 20.2806 9.70906 22.2273C9.33943 22.7695 9.46264 23.4841 10.0048 23.8537C10.5469 24.2234 11.2615 24.1002 11.6312 23.558C12.5183 22.3013 13.9968 21.6359 15.5 21.6359C17.0278 21.6359 18.5064 22.3013 19.3688 23.558C19.7385 24.1002 20.4777 24.2234 20.9952 23.8537C21.5374 23.4841 21.6606 22.7448 21.2909 22.2273C19.911 20.2806 17.6932 19.3196 15.5 19.3196Z" fill="#D52823"/>\n</svg>',
                                2: '<svg width="31" height="31" viewBox="0 0 31 31" fill="#A4A4A4" xmlns="http://www.w3.org/2000/svg">\n<path d="M15.5 0C6.94913 0 0 6.94913 0 15.5C0 24.0509 6.94913 31 15.5 31C24.0509 31 31 24.0509 31 15.5C31 6.94913 24.0755 0 15.5 0ZM15.5 28.5604C8.27981 28.5604 2.46423 22.7202 2.43959 15.5C2.43959 8.27981 8.27981 2.46423 15.5 2.43959C22.7202 2.46423 28.5358 8.27981 28.5604 15.5C28.5358 22.7202 22.7202 28.5604 15.5 28.5604ZM21.4634 10.3744C20.0342 10.3744 18.876 11.5326 18.876 12.9618C18.876 14.3911 20.0342 15.5493 21.4634 15.5493C22.8927 15.5493 24.0509 14.3911 24.0509 12.9618C24.0509 11.5326 22.8927 10.3744 21.4634 10.3744ZM12.1486 12.9618C12.1486 11.5326 10.9905 10.3744 9.56121 10.3744C8.13196 10.3744 6.97377 11.5326 6.97377 12.9618C6.97377 14.3911 8.13196 15.5493 9.56121 15.5493C10.9905 15.5493 12.1486 14.3911 12.1486 12.9618ZM18.8267 20.0095H12.1733C11.5326 20.0095 10.9905 20.527 10.9905 21.1924C10.9905 21.8331 11.5079 22.3752 12.1733 22.3752H18.8267C19.4674 22.3752 20.0095 21.8577 20.0095 21.1924C20.0095 20.527 19.4674 20.0095 18.8267 20.0095Z" fill="#E7AB10"/>\n</svg>',
                                3: '<svg width="31" height="31" viewBox="0 0 31 31" fill="#A4A4A4" xmlns="http://www.w3.org/2000/svg">\n<path d="M15.5 0C6.94913 0 0 6.94913 0 15.5C0 24.0509 6.94913 31 15.5 31C24.0509 31 31 24.0509 31 15.5C31 6.94913 24.0755 0 15.5 0ZM15.5 28.5604C8.27981 28.5358 2.46423 22.7202 2.43959 15.5C2.43959 8.27981 8.27981 2.46423 15.5 2.43959C22.7202 2.46423 28.5358 8.27981 28.5604 15.5C28.5358 22.7202 22.7202 28.5358 15.5 28.5604ZM12.1486 12.9618C12.1486 11.5326 10.9905 10.3744 9.56121 10.3744C8.13196 10.3744 6.97377 11.5326 6.97377 12.9618C6.97377 14.3911 8.13196 15.5493 9.56121 15.5493C10.9905 15.5493 12.1486 14.3911 12.1486 12.9618ZM21.4634 10.3744C20.0342 10.3744 18.876 11.5326 18.876 12.9618C18.876 14.3911 20.0342 15.5493 21.4634 15.5493C22.8927 15.5493 24.0509 14.3911 24.0509 12.9618C24.0262 11.5326 22.8927 10.3744 21.4634 10.3744ZM21.0199 19.5413C20.4777 19.1717 19.7631 19.2949 19.3935 19.837C18.5064 21.0938 17.0278 21.7591 15.5246 21.7591C13.9968 21.7591 12.5183 21.0938 11.6558 19.837C11.2862 19.2949 10.5469 19.1717 10.0294 19.5413C9.48728 19.911 9.36407 20.6502 9.7337 21.1677C11.1137 23.1391 13.3561 24.1002 15.5246 24.0755C17.7178 24.0755 19.9356 23.1145 21.3156 21.1677C21.6606 20.6502 21.5374 19.911 21.0199 19.5413Z" fill="#2FB241"/>\n</svg>',
                                4: "",
                            },
                            c = "",
                            p = 1;
                        p < 5;
                        p++
                    )
                        c += '<div class="col-md-3 ticket-mark mark-' + p + '"  onclick="tickets.mark(' + (4 == p) ? 0 : p + ", " + e + ", " + r + ')">' + l[p] + lang.ticketMark[p] + "</div>";
                    o += '<div class="tickets-marks"><h6>' + lang.ticketMarkLabel + "</h6><div>" + c + "</div></div>";
                }
                (document.getElementById("ticket_messages").innerHTML = o), this.update_counter(e), scrollToBottom($("#ticket_messages"));
            }
        },
        mark: function (e, t, a) {
            1 == e && this.complaint(a),
                $.post("/req/tickets.php", { do: "mark", mark: e, ticket_id: t }, function (e) {
                    0 == e.code ? $(".tickets-marks").html(e.html) : _notify({ message: e.errormsg });
                });
        },
        subject_select: function (e) {
			console.log(e);
            if(e == 1){
				jQuery('#tickets_form tr').show();
			}else if(e == 2){
				jQuery('#tickets_form tr').show();
				jQuery('#tickets_form tr.ticket-field').hide();
			}else if(e == 3){
				jQuery('#tickets_form tr').show();
				jQuery('#tickets_form #order_id_container').hide();
				jQuery('#tickets_form tr.ticket-field').hide();
			}else{
				jQuery('#tickets_form tr').hide();
				jQuery('#tickets_form tr:first-child').show();
			}
        },
        update_counter: function (e) {
            if (document.getElementById("ticket_session" + e)) {
                var t = parseInt(tickets.data.sessions[e].unread);
                t
                    ? $("#ticket_session" + e + " .new-m")
                          .html(t)
                          .show()
                    : $("#ticket_session" + e + " .new-m").hide(),
                    (document.getElementById("ticket_session" + e).dataset.unread = t),
                    tickets.reorderSessions();
                var a = 0;
                for (var i in this.data.sessions) this.data.sessions[i].unread && ++a;
                a && document.getElementById("unread_tickets") && (document.getElementById("unread_tickets").innerHTML = "+" + a), a || $("#unread_tickets").remove();
            }
        },
        session_status: function (e, t) {
            var a = { do: "session_status", ticket_id: e, status: t };
            return (
                $.post("/req/tickets.php", a, function (t) {
                    0 == parseInt(t.code) ? $("#status" + e).html(t.html) : alert(t.errormsg);
                }),
                !1
            );
        },
    }),
    (subscribe_dialog = function (e) {
        return (
            $('<div title="' + e.innerText + '"><iframe src="' + e.href + '" width="480" height="325" frameborder="0"></iframe></div>')
                .appendTo("body")
                .dialog({ modal: !0, width: $("body").width() > 510 ? 510 : "95%" }),
            !0
        );
    }),
    (tags = function (e) {
        if (e) {
            var t = document.querySelectorAll(".question");
            for (var a in t) void 0 !== t[a].dataset && (t[a].dataset.tags.indexOf(e) > -1 ? $(t[a]).addClass("active").removeClass("dn") : $(t[a]).removeClass("active").addClass("dn"));
        } else $(".question").removeClass("active dn");
    }),
    (check_payment = function (e, t) {
        $.post("/req/check_payment.php", { order_id: e, sign: t }, function (e) {
            0 == e.code ? (location.href = e.link) : alert(e.message);
        });
    }),
    (balance = {
        pay: function (e) {
            $(".lk-refill-form__btn").attr("disabled", !0);
            var t = $(e).serializeObject();
            return (
                $.post("/req/balance.php", t, function (e) {
                    0 == e.code ? (parseInt(e.is_html) ? $(e.content).appendTo("body").submit() : (window.location.href = e.content)) : alert(e.message), $(".lk-refill-form__btn").attr("disabled", !1);
                }),
                !1
            );
        },
    }),
    (maxCountSymbolsBidsFormatField = 255);
var inputNumberValidate = function () {
    this.value = this.value
        .replace(/[^\d,.]*/g, "")
        .replace(/([,.])[,.]+/g, "$1")
        .replace(/^[^\d]*(\d+([.,]\d{0,5})?).*$/g, "$1")
        .replace(",", ".");
};
function show_important_info(e) {
    var t = parseInt(window.sessionStorage.getItem("visit")),
        a = parseInt(localStorage.getItem("first_visit"));
    isNaN(a) && isNaN(t)
        ? (localStorage.setItem("first_visit", 1), window.sessionStorage.setItem("visit", 1), $("#info").slideToggle("slow"))
        : a < e && isNaN(t) && (a++, $("#info").slideToggle("slow"), localStorage.setItem("first_visit", a), window.sessionStorage.setItem("visit", 1));
}
function scrollTo(e, t, a) {
    $("html, body")
        .stop()
        .animate({ scrollTop: $(e).offset().top - t }, a);
}

function showErrorsValidateFormatBids(e) {
    try {
        $.each(e, (e, t) => {
            $.each($('[name="' + t.name + '"]'), (a, i) => {
                let o = t.name.slice(0, -2);
                e === $(i).index() - 1 &&
                    ($('<div class="error" id="error_' + e + o + '"><em></em><p>' + t.text + "</p></div>")
                        .appendTo("body")
                        .position({ my: "left bottom+4", at: "left top", of: $(i) }),
                    $(i).bind("keydown", function () {
                        try {
                            $("#error_" + e + o).remove();
                        } catch (e) {}
                        $(i).unbind("keydown");
                    }));
            });
        });
    } catch (e) {}
}
function showErrorsValidateForm(e, t) {
    try {
        var a = 1 + Math.floor(6 * Math.random());
        $('<div class="error" id="error_' + a + '"><em></em><p>' + t + "</p></div>")
            .appendTo("body")
            .position({ my: "left bottom-5", at: "left top", of: e }),
            $(e).bind("click", function () {
                try {
                    $("#error_" + a).remove();
                } catch (e) {}
                $(e).unbind("click");
            });
    } catch (e) {}
}
function emailInputManage(e) {
    var t = $(e.target),
        a = t.closest(".form-line");
    validateEmail(t.val()) ? a.hasClass("line-alert") && a.removeClass("line-alert") : a.hasClass("line-alert") || a.addClass("line-alert");
}
$("[name=partner_cost]").change(inputNumberValidate),
    $("[name=partner_cost]").keyup(inputNumberValidate),
    $(document).ready(function () {
        $("#sortable").sortable({ axis: "y", cursor: "move", handle: ".handle div, td.handle", items: ".sort" });
    }),
    
    jQuery(function (e) {
        var t = e("body");
        t.on("click", "#login_button", function (t) {
            e(".login-error").hide(), e("#login_form .form-line:first").removeClass("line-alert");
        }),
            t.on("click", ".login-open", function (a) {
                function i() {
                    e(".login-shadow").addClass("log-open"), e(".reg-shadow").removeClass("reg-open"), e(".forgot-shadow").removeClass("forgot-open");
                }
                (e(".reg-shadow").length > 0 || e(".forgot-shadow").length > 0 || e(".login-shadow").length > 0 || e("#recaptcha_tickets").length || e("#recaptcha_authorisation_page").length) &&
                    (
                    e("#recaptcha_authorisation_inactive").attr("id", "recaptcha_authorisation"),
                    e("#recaptcha_registration").attr("id", "recaptcha_registration_inactive"),
                    e("#recaptcha_forgot_password").attr("id", "recaptcha_forgot_password_inactive"),
                    e("#recaptcha_tickets").attr("id", "recaptcha_tickets_inactive"),
                    e("#recaptcha_authorisation_page").attr("id", "recaptcha_authorisation_page_inactive")),
                    a.preventDefault(),
                    e(".login-shadow").length > 0
                        ? i()
                        : e.post("/req/deferred.php", { section: "login" }, function (e) {
                              0 == e.code && (t.append(e.content), i());
                          });
            }),
            t.on("click", ".close-login", function () {
                sessionStorage.removeItem("after_login_href"), e("div.error.dn").remove(), e(".login-shadow").removeClass("log-open"), e(".login-error").hide(), e("#login_form .form-line:first").removeClass("line-alert");
            }),
            t.on("click", ".login-shadow", function (t) {
                t.target == t.currentTarget && (e("div.error.dn").remove(), e(".log-open").removeClass("log-open"));
            }),
            t.on("click", "#log-email", function () {
                e("#login_form .form-line:first").removeClass("line-alert"), e(".login").removeClass(".login-error"), e(".login-error").hide();
            }),
            t.on("click", ".registration-open", function (t) {
                function a() {
                    e(".reg-shadow").addClass("reg-open"), e(".login-shadow").removeClass("log-open"), e(".forgot-shadow").removeClass("forgot-open");
                }
                (e(".reg-shadow").length > 0 || e(".forgot-shadow").length > 0 || e(".login-shadow").length > 0 || e("#recaptcha_tickets").length || e("#recaptcha_authorisation_page").length) &&
                    (
                    e("#recaptcha_authorisation").attr("id", "recaptcha_authorisation_inactive"),
                    e("#recaptcha_registration_inactive").attr("id", "recaptcha_registration"),
                    e("#recaptcha_forgot_password").attr("id", "recaptcha_forgot_password_inactive"),
                    e("#recaptcha_tickets").attr("id", "recaptcha_tickets_inactive"),
                    e("#recaptcha_authorisation_page").attr("id", "recaptcha_authorisation_page_inactive")),
                    t.preventDefault(),
                    e(".reg-shadow").length > 0
                        ? a()
                        : e.post("/req/deferred.php", { section: "registration" }, function (t) {
                              0 == t.code && (e("body").append(t.content), a());
                          });
            }),
            t.on("click", ".close-registration", function () {
                e("div.error.dn").remove(), e(".reg-shadow").removeClass("reg-open");
            }),
            t.on("click", ".reg-shadow", function (t) {
                t.target == t.currentTarget && (e("div.error.dn").remove(), e(".reg-open").removeClass("reg-open"));
            }),
            t.on("click", "#reg_email", function () {
                e("#registration_form .form-line:first").removeClass("line-alert"), e(".registration").removeClass(".registration-error"), e(".registration-error").hide();
            }),
            t.on("click", ".forgot_password", function (a) {
                function i() {
                    e(".forgot-shadow").addClass("forgot-open"), e(".login-shadow").removeClass("log-open"), e(".reg-shadow").removeClass("reg-open");
                }
            }),
            t.on("click", ".close-forgot", function () {
                e("div.error.dn").remove(), e(".forgot-shadow").removeClass("forgot-open");
            }),
            t.on("click", ".forgot-shadow", function (t) {
                t.target == t.currentTarget && (e("div.error.dn").remove(), e(".forgot-open").removeClass("forgot-open"));
            }),
            e(".close-modal").click(function () {
                e(".modal-shadow").removeClass("modal-open");
            }),
            e(".modal-shadow").click(function (t) {
                t.target == t.currentTarget && e(".modal-open").removeClass("modal-open");
            });
        var a = 0;
        /*t.on("click", ".basket-button", function (e) {
            e.preventDefault(), t.css({ opacity: 0.5 }), 5 == ++a && t.append('<img id="happy-zuckerberg" src="/img/happy_zuckerberg.png">');
            var i = this.dataset.id;
            orders.basket(i);
        }),*/
            t.on("keyup", ".price_recalculate, .phone-input-number", function () {
                orders.price();
            }),
            t.on("click", ".price_recalculate, .phone-input-number", function () {
                orders.price();
            }),
            t.on("change", ".price_recalculate, .phone-input-number", function () {
                orders.price();
            }),
            t.on("change", "#sum_refill", function () {
                psFilter();
            }),
            t.on("keyup", "#sum_refill", function () {
                psFilter();
            }),
            t.on("click", ".subscribe_button", function () {
                var t = this.dataset.id,
                    a = e(this).parents(".soc-body").find(".soc-text p").text();
                orders.subscribers_dialog(t, a);
            }),
            t.on("click", ".important_link", function (t) {
                t.preventDefault(), e("#info").slideToggle("slow"), e(this).parent().toggleClass("active");
            }),
            t.on("submit", "#form-main-ticket", function (t) {
                t.preventDefault();
                var a = e(this);
                try {
                    e('button[type="submit"]', a).prop("disabled", !0);
                    var i = tickets.data.secret;
                    tickets.data.secret || tickets.data.check_access || (i = tickets.data.sessions[tickets.data.session_id].secret);
                    var o = e.extend(a.serializeObject(), { do: "send_message", ticket_id: tickets.data.session_id, secret: i });
                    e.post({
                        url: ["/req/tickets.php"],
                        type: "post",
                        data: o,
                        success: function (t) {
                            void 0 !== t.result && t.result && tickets.onmessage(t.result),
                                void 0 !== t.code &&
                                    0 === parseInt(t.code) &&
                                    (e('button[type="submit"]', a).prop("disabled", !1),
                                    (document.getElementById("message").value = ""),
                                    (document.getElementById("attachments").innerHTML = ""),
                                    moveCaretToEnd(document.getElementById("message"))),
                                void 0 !== t.errormsg && alert(t.errormsg);
                        },
                        error: function (e, t) {
                            console.log(e), console.log(t);
                        },
                        complete: function () {},
                    });
                } catch (t) {
                    console.log(t);
                }
            }),
            t.on("input", '#new_bid_form [name="format[]"]', function (t) {
                if ((t.preventDefault(), e(this).val().length > maxCountSymbolsBidsFormatField))
                    return (
                        void 0 !== lang.maxCountSymbolsBidsFormatField ? alert(lang.maxCountSymbolsBidsFormatField) : alert("The maximum number of characters in the Format field is 255"),
                        e(this).val(e(this).val().slice(0, maxCountSymbolsBidsFormatField)),
                        !1
                    );
            }),
            t.on("submit", "#new_bid_form", function (t) {
                t.preventDefault();
                var a = !1;
                e('#new_bid_form [name="categories[]"]').length &&
                    e.each(e('#new_bid_form [name="categories[]"]'), function (t, i) {
                        null === e(i).val() && (showErrorsValidateForm(e(i), lang.bidsErrorCategory), (a = !0));
                    }),
                    !a &&
                        e('#new_bid_form [name="description[]"]').length &&
                        e.each(e('#new_bid_form [name="description[]"]'), function (t, i) {
                            e(i).val() || (showErrorsValidateForm(e(i), lang.bidsErrorDescription), (a = !0));
                        }),
                    !a &&
                        e('#new_bid_form [name="country[]"]').length &&
                        e.each(e('#new_bid_form [name="country[]"]'), function (t, i) {
                            e(i).val() || (showErrorsValidateForm(e(i), lang.bidsErrorCountry), (a = !0));
                        }),
                    !a &&
                        e('#new_bid_form [name="format[]"]').length &&
                        e.each(e('#new_bid_form [name="format[]"]'), function (t, i) {
                            e(i).val() || (showErrorsValidateForm(e(i), lang.bidsErrorPartnerFormat), (a = !0));
                        }),
                    !a &&
                        e('#new_bid_form [name="partner_cost[]"]').length &&
                        e.each(e('#new_bid_form [name="partner_cost[]"]'), function (t, i) {
                            e(i).val() || (showErrorsValidateForm(e(i), lang.bidsErrorPartnerCost), (a = !0));
                        }),
                    a ? t.stopPropagation() : bids.new_bid(e(this));
            }),
            t.on("submit", ".2_step_user_offer", function (t) {
                t.preventDefault();
                var a = !1;
                e(this).find('[name="categories[]"]').length &&
                    e.each(e(this).find('[name="categories[]"]'), function (t, i) {
                        null === e(i).val() && (showErrorsValidateForm(e(i), lang.bidsErrorCategory), (a = !0));
                    }),
                    !a &&
                        e('[name="description[]"]').length &&
                        e.each(e('[name="description[]"]'), function (t, i) {
                            e(i).val() || (showErrorsValidateForm(e(i), lang.bidsErrorDescription), (a = !0));
                        }),
                    !a &&
                        e('[name="country[]"]').length &&
                        e.each(e('[name="country[]"]'), function (t, i) {
                            e(i).val() || (showErrorsValidateForm(e(i), lang.bidsErrorCountry), (a = !0));
                        }),
                    !a &&
                        e('[name="format[]"]').length &&
                        e.each(e('[name="format[]"]'), function (t, i) {
                            e(i).val() || (showErrorsValidateForm(e(i), lang.bidsErrorPartnerFormat), (a = !0));
                        }),
                    !a &&
                        e('[name="supply_count[]"]').length &&
                        e.each(e('[name="supply_count[]"]'), function (t, i) {
                            e(i).val() || (showErrorsValidateForm(e(i), lang.newOffersErrorValue), (a = !0));
                        }),
                    a ? t.stopPropagation() : ajaxformDelivery(e(this));

                    console.log('fdffffffffffff');
            });
    }),
    $("#orders_search_form").submit(function (e) {
        e.preventDefault();
        var t = document.location.href.split("?");
        document.location.href = t[0];
    }),
    $(".alert.notifications-block").on("closed.bs.alert", function () {
        var e = $(this).data("id"),
            t = $(this).data("req"),
            a = $(this).data("act");
        $.post("/req/" + t + ".php", { do: a, ns_id: e }, function (e) {
            e.message && location.reload();
        });
    }),
    $(".notifications-block.not-read:not(.alert)").on("click", function () {
        var e = $(this),
            t = e.data("id"),
            a = e.data("req"),
            i = e.data("act");
        $.post("/req/" + a + ".php", { do: i, ns_id: t }, function (t) {
            t.message && (e.removeClass("not-read"), location.reload());
        });
    });
var emailField = "input[name=email]";
function confirmWallet(e, t) {
    e &&
        ((t.disabled = !0),

        $.post(
            "/req/form.php",
            { section: "confirm_wallet", code: e },
            function (t) {
                1 == t.code &&
                    $(".wallet_" + e).each(function (e, t) {
                        var a = $(t).find(".new-wallet").val(),
                            i = $(t).find(".wallet");
                        i.val(a), $(t).find("div").remove(), $(t).removeClass("row").append(i.prop("disabled", !1));
                    });
            },
            "json"
        ));
        console.log(e);
        console.log(t);
        console.log('fdsafdsafds');
}
function tpanel_add_comment(e) {
    var t = $("#tpanel_comment").text();
    console.log(t);
    var a = prompt("Введите комментарий", t);
    $.post("/req/tickets.php", { do: "ticket_comment", comment: a, id: e }), $("#tpanel_comment").text(a);
}
function selectCategory(e) {
    var t = $('<div class="selectCategory"/>'),
        a = $('<select style="margin-bottom:20px;" id="parent"/>');
    return (
        a.append('<option value="0">Выбрать категорию</option>'),
        $.each(_categories, function (t, i) {
            var o = $('<optgroup label="' + i.name + '"/>'),
                n = "";
            $.each(i.items, function (t, a) {
                e == a.id && ((n = ' selected="selected"'), (e = 0)), o.append('<option data-hidden="' + a.hidden + '" value="' + a.id + '"' + n + ">" + a.name + "</option>"), (n = "");
            }),
                a.append(o);
        }),
        t.append(a),
        a.select2({ dropdownAutoWidth: !0, templateResult: formatState }),
        t
    );
}
function formatState(e) {
    var t = parseInt($(e.element).attr("data-hidden"));
    return e.children ? e.text : e.children ? void 0 : 0 === t ? e.text : $('<span><img class="hidden_goods" src="/img/icons/hidden.png"> ' + e.text + "</span>");
}
function toast(e, t) {
    var a = $("<div>")
        .addClass("alert alert-" + t)
        .attr("role", "alert")
        .css({ position: "fixed", right: "-600px", top: "10px", "z-index": "999" });
    a.html(e), $("body").append(a);
    var i = $(".alert");
    i.animate({ right: "10px" }, 300),
        window.setTimeout(function () {
            i.animate({ right: "-600px", opacity: 0 }, 300, "swing", function () {
                i.remove();
            });
        }, 5e3);
}
function psFilter() {
    var e = +parseFloat($("#sum_refill").val()).toFixed(2);
    (paymentSystem = $("form [name=payment_system]").val()),
        "NaN" == e &&
            $(".ps_container").each(function () {
                $(".list-payments").fadeIn("slow");
            }),
        $(".ps_container").each(function () {
            var t = +parseFloat($(this).attr("data-min_payment_" + currentCurrency)).toFixed(2),
                a = +parseFloat($(this).attr("data-max_payment_" + currentCurrency)).toFixed(2);
            e < t || (a > 0 && e > a) ? ($(this).parent().fadeOut("fast"), $(this).addClass("ps_hidden")) : ($(this).parent().fadeIn("slow"), $(this).removeClass("ps_hidden"));
        }),
        $("div .active-refill").hasClass("ps_hidden") && ($("div .ps_hidden").removeClass("active-refill"), $("form [name=payment_system]").val(0)),
        paymentSystem == psQiwi ? addPhoneInput() : removePhoneInput();
}
function addPhoneInput() {
    $("input[name=phone]").prop("required", !0), $(".phone-number").show();
}
function removePhoneInput() {
    $("input[name=phone]").prop("required", !1), $(".phone-number").hide();
}
function open_telegram() {
    $.post("/req/two_auth.php", { section: "telegram_open" }, function (e) {
        if (0 == e.code) {
            $(".pa-modal-overlay").removeClass("act"), $(".two-auth-modal").removeClass("act");
            window.open("https://t.me/accountsseller_auth_code_bot", "_blank");
        }
    });
}
function two_auth_switch_off() {
    $.post("/req/two_auth.php", { section: "two_auth_off" }, function (e) {});
}
function two_auth_switch_on() {
    var e = $('input[name="confirm_auth"]').val();
    $.post("/req/two_auth.php", { section: "two_auth_on", password: e }, function (t) {
        if ((2 == t.code && location.reload(), 0 == t.code)) {
            $(".pa-modal-overlay").removeClass("act"), $(".two-auth-modal").removeClass("act");
            window.open("https://t.me/accountsseller_auth_code_bot", "_blank");
        }
        e.length < 1 ? ($(".no-proxy-body").addClass("error-auth"), $('input[name="confirm_auth"]').addClass("error-field")) : "error" !== t.code && ($(".error-auth").show(), $('input[name="confirm_auth"]').addClass("error-field")),
            setTimeout(function () {
                $(".no-proxy-body").removeClass("error-auth"), $('input[name="confirm_auth"]').removeClass("error-field"), $(".error-auth").hide();
            }, 3e3);
    });
}
$(emailField).keyup(function (e) {
    emailInputManage(e);
}),
    $(emailField).change(function (e) {
        emailInputManage(e);
    }),
    $(emailField).blur(function (e) {
        emailInputManage(e);
    }),
    $("#referral_form").submit(function (e) {
        e.preventDefault();
        var t = $("#referral_form").serializeObject();
        $('button[type="submit"]').attr("disabled", "disabled"),
            $.post("/req/" + (void 0 === t.act ? "form" : t.act) + ".php", t, function (e) {
                _notify_modal({ icon: "notify", message: e.message }), $('button[type="submit"]').removeAttr("disabled");
            });
    }),
    jQuery(function (e) {
        if (e.ui && e.ui.dialog && e.ui.dialog.prototype._allowInteraction) {
            var t = e.ui.dialog.prototype._allowInteraction;
            e.ui.dialog.prototype._allowInteraction = function (a) {
                return !!e(a.target).closest(".select2-dropdown").length || t.apply(this, arguments);
            };
        }
    }),
    $("img.img-svg").each(function () {
        var e = $(this),
            t = e.attr("class"),
            a = e.attr("src");
        $.get(
            a,
            function (a) {
                var i = $(a).find("svg");
                void 0 !== t && (i = i.attr("class", t + " replaced-svg")),
                    !(i = i.removeAttr("xmlns:a")).attr("viewBox") && i.attr("height") && i.attr("width") && i.attr("viewBox", "0 0 " + i.attr("height") + " " + i.attr("width")),
                    e.replaceWith(i);
            },
            "xml"
        );
    }),
    (window.onerror = function (e) {
        (e.toString().includes("reCAPTCHA") || e.toString().includes("reCapScripts")) && toast(lang.recaptchaError, "danger");
    }),
    $("input").is("#sum_refill") && psFilter(),
    $(".download-link").click(function () {
        $(this).addClass("disabled-link"),
            setTimeout(
                function (e) {
                    $(e).removeClass("disabled-link");
                },
                1e4,
                $(this)
            );
    }),
    $.ajaxSetup({
        beforeSend: function (e, t) {
            var a = $('meta[name="csrf-token"]').attr("content");
            e.setRequestHeader("X-CSRF-TOKEN", a);
        },
    }),
    $("#two-factor-toggle").change(function () {
        $(this).is(":checked") ? ($(".pa-modal-overlay").addClass("act"), $(".two-auth-modal").addClass("act")) : two_auth_switch_off();
    }),
    $(".pa-modal__cross, .cancel-two-auth").click(function () {
        two_auth_switch_off(), $("#two-factor-button").trigger("click"), $(".pa-modal-overlay").removeClass("act"), $(".two-auth-modal").removeClass("act");
    }),
    $(".ok-two-auth").click(function () {
        two_auth_switch_on();
    }),
    $(".more-activity").click(function () {
        $(".history-text").removeClass("hidden-text"), $(this).hide();
    });


    $('[data-toggle="tooltip"]').tooltip({
        placement : 'top'
    }); 
   

   $(document).on('submit', '#new_ticket_form', function(){

        var input_data = $(this).serialize();

        console.log(input_data);

        $('#new_ticket_form').find('button').attr('disabled');

        $.ajax({
            type: "POST",
            url: my_ajax_object.ajax_url,
            dataType : 'json',
            data: input_data,
            success: function(response) {
                console.log(response);
                if(response.err){
                    $('.tickets_err').html(response.err);
                    $('#new_ticket_form').find('button').removeAttr('disabled')
                }else{
                    window.location.replace(response.redirect);
                }

            }
        });

        return false;

   });

   /*$('.chat-section > div').animate({
        scrollTop: $('.chat-section > div').get(0).scrollHeight
    }, 1000);*/

    // When the Upload button is clicked...
    $(document).on('change', '#file_trigger', function(e){
          e.preventDefault;

          var files_data = $('#file_trigger'); 
         
          $this = $(this);
          file_data = $(this).prop('files')[0];
          form_data = new FormData();
          //form_data.append('filess', file_data);

          $.each($(files_data), function(i, obj) {
                $.each(obj.files,function(j,file){
                    form_data.append('files[' + j + ']', file);
                })
            });
         
          // our AJAX identifier
          form_data.append('action', 'upload_attachments');  

          console.log(files_data);
         

          $.ajax({
              type: 'POST',
              url: my_ajax_object.ajax_url,
              data: form_data,
              contentType: false,
              processData: false,
              success: function(response){
                  console.log(response);
                  $('#attachments').append(response); 
                  
              }
          });
    });

    
    


   $(document).on('submit', '#form-ticket-send-msg-js', function(){

        var input_data = $(this).serialize();

        console.log(input_data);

        $('#form-ticket-send-msg-js').find('button').css({
            'pointer-events' : 'none', 
            'opacity' : '.5'
        });


        $.ajax({
            type: "POST",
            url: my_ajax_object.ajax_url,
            dataType : 'json',
            data: input_data,
            success: function(response) {
                console.log(response);
                $('#form-ticket-send-msg-js textarea[name="message"]').val('');
                $('#attachments').html('');
                $('#form-ticket-send-msg-js').find('button').css({
                    'pointer-events' : 'inherit', 
                    'opacity' : '1'
                });

                $('.ticket-chat').append(response.data.chat_html);
                
                $('.chat-section > div').animate({
                    scrollTop: $('.chat-section > div').get(0).scrollHeight
                }, 1000);

            }
        });

        return false;

   });
    //solved unsolved
    // $(document).on('click', '#solved', function (event) {
    //     event.preventDefault();
    //     $("#problem").hide();
    //     $("#solution").show();
    // });
    
    // $(document).on('click', '#unsolved', function (event) {
    //     event.preventDefault();
    //     $("#problem").show();
    //     $("#solution").hide();
    // });
    
    

   $(document).on('click', '.attachments .close', function(){

        $(this).parent('div').remove();;

   });


   $(document).on('click', '.ticket_list_item', function(){

        var id = $(this).attr('data-id');
        var admin_chat = $(this).attr('data-admin');

        console.log(admin_chat);

        $('.ticket_list_item').removeClass('active');
        $(this).removeClass('has_unread_chat');
        $(this).addClass('active');

        $.ajax({
          type: 'POST',
          url: my_ajax_object.ajax_url,
          data: {
            'action': 'show_ticket_chat_list', 
            'id': id,
            'admin_chat': admin_chat
          },
          dataType : 'json',
          success: function(response){
              console.log(response);
              $('.ticket-chat').html(response.chat_list_html);
              $('#ticket_form').html(response.chat_form_html);
               $('.chat-section > div').animate({
                    scrollTop: $('.chat-section > div').get(0).scrollHeight
                }, 1000);
              
          }
      });

   });

   


   $(document).on('keyup', '.ticket-list-user-down input[name="search"]', function(){

        var keyword = $(this).val();

        var admin_chat = $(this).attr('data-admin');

        console.log(keyword);

        $.ajax({
          type: 'POST',
          url: my_ajax_object.ajax_url,
          data: {
            'action': 'show_ticket_list_search', 
            'keyword': keyword,
            'admin_chat': admin_chat,
          },
          dataType : 'html',
          success: function(response){
              console.log(response);
              $('[data-my-chats-js]').html(response);
              
          }
      });

   });

   $(".format_fi").selectize({
        plugins: ["drag_drop"],
    });

   $('#tags').select2().change(function(e){
        tags(document.getElementById('tags').options[document.getElementById('tags').selectedIndex].value);
    });

   $(document).on('submit', '.update_payment_profile', function(){

        var input_data = $(this).serialize();

        console.log(input_data);

        $('.update_payment_profile').find('button').css({
            'pointer-events' : 'none', 
            'opacity' : '.5'
        });
        $('.update_suc').html(' ');


        $.ajax({
            type: "POST",
            url: my_ajax_object.ajax_url,
            dataType : 'html',
            data: input_data,
            success: function(response) {
                console.log(response);

                if(response != 1){
                    $('.update_suc').html('Fill one payment detail for funds withdrawals.');
                }else{
                    $('.update_suc').html('Your payment information has been updated. ');
                    location.reload();
                }
                $('.update_payment_profile').find('button').css({
                    'pointer-events' : 'inherit', 
                    'opacity' : '1'
                });
                
            }
        });

        return false;

   });


   $(document).on('submit', '.deduct_partner_payment', function(){

        var input_data = $(this).serialize();
        var dis = $(this);
         dis.find('.deduct_msg').html('');

        dis.find('button').css('pointer-events', 'none');

        $.ajax({
              type: 'POST',
              url: my_ajax_object.ajax_url,
              data: input_data,
              dataType : 'html',
              success: function(response){
                  console.log(response);
                  $('.deduct_partner_payment')[0].reset();
                 dis.find('.deduct_msg').html('Partner payment deducted.'); 
                 dis.find('button').css('pointer-events', 'inherit');
              }
        });

        return false;

   });

   /*$('.search_status_by select').on('change', function(){

        $('.search_status_by').submit();

   });*/

   $(document).on('click', '.upload_partner_account', function () {
    var bidid = $(this).attr('data-id');
    $('.parnter_upload_popup').css('display', 'flex');
    $('.partner_upload_action input[name="bid_id"]').val(bidid);
});

$(document).on('submit', '.partner_upload_form', function () {
    var $form = $(this);

    $form.find('button[type="submit"]').prop('disabled', true);

    var data = $form.serialize();

    $("#loaderr").show();

    $.ajax({
        type: "POST",
        url: my_ajax_object.ajax_url,
        dataType: 'json',
        data: data,
        success: function (response) {
            console.log(response);
            console.log(response.match_found);

            if (response.match_found) {
                alert("Existing account found. Please check the accounts and try again.");
            } else {
                // Success: Update the form content
                $form.html('The Accounts are queued for uploading. This usually takes only a few minutes. You will receive a notification by email after the accounts are uploaded.');
                $('#status_name' + response.bid_id).text('Checking Accounts');
                $('#status_name' + response.bid_id).next().hide();

                // Hide the loader when the request is successful
                $('#loaderr').hide();
            }
        },
        error: function (error) {
            // Handle AJAX error here if needed
            console.error("Error:", error);
            // Re-enable the submit button on error
            $form.find('button[type="submit"]').prop('disabled', false);
        }
    });

    return false;
});


$(document).on('click', '.partner_upload_close, .partner_close', function () {
    $('.parnter_upload_popup').hide();
});


   $(document).on('click', '.change_partner_price', function(){

        var proid = $(this).attr('data-id');
         var itemid = $(this).attr("item-id");

        $('.partner_price_popup').css('display', 'flex');
        $('.partner_price_popup input[name="product_id"]').val(proid);
         $('.partner_price_popup input[name="item_id"]').val(itemid);

   }); 

   $(document).on('click', '.price_close', function(){

        $('.partner_price_popup').hide();

   });

    $(document).on('submit', '.pp_price_form', function(){

        $(this).find('button[type="submit"]').css('pointer-events', 'none');

        var data = $(this).serialize();
        var price = $(this).find('input[name="price"]').val();
        var aidi = $(this).find('input[name="product_id"]').val();

        console.log(price);
        console.log(aidi);



        $.ajax({
            type: "POST",
            url: my_ajax_object.ajax_url,
            dataType : 'html',
            data: data,
            success: function(response) {

                //window.location.href = "/partner";
                $('.pp_price_form').html('The price has been changed.');
                $('.change_partner_price[data-id="'+ aidi +'"]').html(price + ' USD');

               

            }
        });

        return false;

   }); 


    $(document).on('click', '#place_order', function(e) {
      

      $('#preloader').show();
    
    });

    // Home page item view toggle on click function
    $(document).on('click', '.expand_subcat_button', function() {
        // Find the parent .socs container
        var socsContainer = $(this).closest('.socs');

        // Toggle visibility of items within the specific category
        socsContainer.find('.first_div, .new_div_toggle').toggle();

        // Toggle the button text
        if ($(this).hasClass('hide_subcat')) {
            $(this).removeClass('hide_subcat').html('View All');
        } else {
            $(this).addClass('hide_subcat').html('Hide accounts');
        }
    });

    jQuery(document).ready(function($){
        $('form[id^="subscribe-form"]').on('submit', function(e){
            e.preventDefault();

            let form = $(this);
            let email = form.find('input[name = "subscriber_email"]').val();
            let action = form.find('input[name = "action"]').val();

            $.ajax({
                url: my_ajax_object.ajax_url,
                type: 'POST',
                data: {
                    action: action,
                    subscriber_email: email
                },
                success: function(response){
                    if(response.success){
                        alert(response.data.message);
                        form[0].reset();
                        form.closest('.modal').modal('hide');
                    }else{
                        alert(response.data.message);
                    }
                },
                error: function(){
                    alert('There was an error processing your request. Please try again.');
                }
                
            });
        });
    });
    // Partner cost script
    jQuery(document).ready(function($) {
        // Function to show loading indicator
        function showLoading() {
            $('#partner_cost_ammount').html('<div class="loading-indicator"><strong>Amount to withdraw is Calculating...</strong></div>');
        }
    
        // Function to fetch partner cost HTML via AJAX
        function fetchPartnerCost() {
            var data = {
                'action': 'get_partner_cost'
            };
    
            $.get(partnerCostAjax.ajaxurl, data, function(response) {
                $('#partner_cost_ammount').html(response);
            });
        }
    
        // Initially show loading indicator
        showLoading();
    
        // Fetch partner cost HTML
        fetchPartnerCost();
    });
    
    
    
    
    
})(jQuery);


