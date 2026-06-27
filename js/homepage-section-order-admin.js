(function ($) {
	'use strict';

	$(function () {
		var $list = $('#homepage-section-order-list');
		var $button = $('#save-homepage-section-order');
		var $status = $('#homepage-section-order-status');

		if (!$list.length || !$button.length) {
			return;
		}

		$list.sortable({
			axis: 'y',
			handle: '.homepage-section-handle',
			placeholder: 'homepage-section-placeholder',
			forcePlaceholderSize: true,
		});

		$button.on('click', function () {
			var order = [];

			$list.find('.homepage-section-item').each(function () {
				order.push($(this).data('section-id'));
			});

			if (!order.length) {
				$status.text(bcmarketHomepageSectionOrder.i18n.empty);
				return;
			}

			$button.prop('disabled', true);
			$status.text(bcmarketHomepageSectionOrder.i18n.saving);

			$.post(bcmarketHomepageSectionOrder.ajaxUrl, {
				action: 'bcmarket_save_homepage_section_order',
				nonce: bcmarketHomepageSectionOrder.nonce,
				order: order,
			})
				.done(function (response) {
					if (response && response.success) {
						$status.text(response.data.message || bcmarketHomepageSectionOrder.i18n.saved);
					} else {
						$status.text(
							(response && response.data && response.data.message) ||
								bcmarketHomepageSectionOrder.i18n.error
						);
					}
				})
				.fail(function () {
					$status.text(bcmarketHomepageSectionOrder.i18n.error);
				})
				.always(function () {
					$button.prop('disabled', false);
				});
		});
	});
})(jQuery);
