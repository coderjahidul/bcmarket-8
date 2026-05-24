function set_pp_lang(t) {
    jQuery(".paypal_content").html(jQuery("#info_" + t).val());
}
jQuery(document).ready(function ($) {
    $(".faq_min-btn-open").click(function () {
        (id_open = $(this).attr("data-id")),
            $("#" + id_open).hasClass("hide")
                ? ($("#" + id_open).removeClass("hide"), $(this).find(".faq_min-icon-open").addClass("hide"), $(this).find(".faq_min-icon-close").removeClass("hide"), $(this).css("font-weight", "bold"))
                : ($("#" + id_open).addClass("hide"), $(this).find(".faq_min-icon-close").addClass("hide"), $(this).find(".faq_min-icon-open").removeClass("hide"), $(this).css("font-weight", "inherit"));
    }),
        $("body").on("click", ".ui-dialog-titlebar-close", function (t) {
            t.preventDefault(), $(".ui-dialog").remove(), $("#subscribers_dialog").remove();
        }),
        $("body").on("click", ".ticket_icon", function (t) {
            t.preventDefault();
            var e = $(this).attr("data-icon_id"),
                n = $(".t_icons").attr("data-ticket_id"),
                i = $(this).attr("src");
            $("#ticket_session_image" + n).html('<img class="order_img" src="' + i + '" />'), $.post("/req/tickets.php", { do: "change_icon", icon_id: e, ticket_id: n }, function (t) {});
        }),
        $("body").on("click", "#partner_payment_button", function (t) {
            console.log(t);
            t.preventDefault();

            var user_id = $(this).attr('data-user');
            console.log(user_id);
            
            $.ajax({
                type: 'POST',
                url: my_ajax_object.ajax_url,
                data: {
                    'action': 'withdraw_request', 
                    'user_id': user_id,
                },
                dataType : 'json',
                success: function(response){
                    console.log(response);
                    
                    var t = $("<p>Your withdrawal request has been accepted. The withdrawal will be made within 3 days on one of the e-wallets indicated in your account.</p>");

                    t.dialog({
                        modal: !0,
                        width: $("body").width() > 400 ? 800 : "95%",
                        close: function () {
                            t.remove();
                            window.location.href = "/partner/payments/";
                        },
                        open: function () {
                            $(".ui-widget-overlay").click(function () {
                                t.remove();
                                window.location.href = "/partner/payments/";
                            });
                        },
                        buttons: {
                            Ok: function() {
                                t.remove();
                                window.location.href = "/partner/payments/";
                            }
                        }
                    });
                }
            });

        }),
        $("body").on("click", ".remove_ticket_icon", function (t) {
            t.preventDefault();
            var e = $(".t_icons").attr("data-ticket_id");
            $(this).attr("src");
            $("#ticket_session_image" + e).html(""), $.post("/req/tickets.php", { do: "change_icon", icon_id: 0, ticket_id: e }, function (t) {});
        }),
        $("#select_all_subs").click(function () {
            var t = this.checked;
            $(":checkbox").prop("checked", t);
        }),
        $("body").on("click", ".pp_button", function (t) {
            if ((t.preventDefault(), !document.getElementById("pp_checkbox").checked)) return alert("ru" == $.cookie("lang") ? "Необходимо принять согласие с условиями" : "It's necessary to accept the agreement with the conditions"), !1;
            location.href = $("#pp_link").val();
        }),
        $("body").on("click", "#paypal_link", function (t) {
            t.preventDefault();
            var e = $(
                '<div id="paypal_dialog" title="Information" style="display:none"><div id="language" style="background:#fff;text-align:right;margin-top:10px;padding: 0;"><a class="en" href="#" onclick="set_pp_lang(\'en\')" style="color:#000;">Eng</span><a class="ru" href="#" style="color:#000;" onclick="set_pp_lang(\'ru\')">Рус</a><a class="cn" href="#" style="color:#000;" onclick="set_pp_lang(\'cn\')">中國</a></div><span class="paypal_content"></span></div>'
            )
                .appendTo("body")
                .dialog({
                    autoOpen: !0,
                    open: function () {
                        $(".ui-widget-overlay").click(function () {
                            e.remove();
                        });
                    },
                    modal: !0,
                    width: $("body").width() > 800 ? 800 : "95%",
                    maxHeight: "90%",
                });
            $("." + $.cookie("lang")).trigger("click");
        });
    $("#addrow").on("click", function () {
        var t = $("table.order-list >tbody >tr").length,
            e = $("table.order-list")
                .find("tr:eq(" + t + ")")
                .clone();
        e.find("td.del").remove(), e.append('<td class="del"><div class="thead desktop-hide">' + lang.del + '</div><input type="button" class="ibtnDel btn btn-md btn-danger" value="&times;"></td>'), $("table.order-list").append(e);
        console.log(t);

        e.find('.account_format_row').html('<div class="thead desktop-hide"> Accounts format <span>*</span> <div class="help" data-help="example: login:password:email:emails password"></div></div><select class="format_fi" name="format['+ t +'][]" multiple> <option value="">Select</option><option value="login">Login ID</option><option value="username">Username</option><option value="email">Email ID</option><option value="password">Passoword</option><option value="alt_email">Alt Email</option><option value="phone">Phone</option><option value="dob">Date of Birth</option><option value="gender">Gender</option><option value="cookies">Cookies</option><option value="gauth">Google Authentication Code</option></select>');


        $(".format_fi[name='format["+ t +"][]']").selectize({
            plugins: ["drag_drop"],
        });
    }),
        $("table.order-list").on("click", ".ibtnDel", function (t) {
            $(this).closest("tr").remove(), $(".error").remove();
        }),
        $("body").on("click", "#subscribe", function (t) {
            $(this).prop("checked")
                ? ("ru" == $.cookie("lang") ? $(".buy_button #buy_button_text").text("Подписаться и продолжить покупку") : $(".buy_button #buy_button_text").text("Subscribe and checkout"),
                  "ru" == $.cookie("lang")
                      ? $("#info_subscribe").html('<span class="red">После нажатия кнопки "подписаться и продолжить покупку" вы должны будете подтвердить подписку на почте!</span>')
                      : $("#info_subscribe").html('<span class="red">After selecting "Subscribe and checkout" button, you will have to confirm the subscription by e-mail!</span>'))
                : ("ru" == $.cookie("lang") ? $(".buy_button #buy_button_text").text("Перейти к оплате") : $(".buy_button #buy_button_text").text("Proceed to checkout"), $("#info_subscribe").text(""));
        }),
        $("body").on("click", "#accept_partner", function (t) {
            t.preventDefault();
            var e = $(this).attr("data-partner_id");
            $.post("/req/form.php", { section: "accept_partner", partner_id: e, active: 1 }, function (t) {
                location.href = "/ru/admin/users";
            });
        }),
        $("body").on("click", "#decline_partner", function (t) {
            t.preventDefault();
            var e = $(this).attr("data-partner_id"),
                n = prompt("Введите причину отказа");
            $.post("/req/form.php", { section: "accept_partner", partner_id: e, active: 0, reason: n }, function (t) {});
        }),
        $("body").on("click", "#update_currency_usd", function (t) {
            t.preventDefault(),
                $.post("/req/form.php", { section: "update_currency", currency: "USD" }, function (t) {
                    $("#value2").val(t);
                });
        });
});
