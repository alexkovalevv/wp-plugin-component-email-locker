<?php

	/**
	 * Subscription Options for Email Locker
	 *
	 * Created via the Factory Metaboxes.
	 *
	 * @author Paul Kashtanoff <paul@byonepress.com>
	 * @copyright (c) 2014, OnePress Ltd
	 *
	 * @package core
	 * @since 1.0.0
	 */
	class OPanda_SubscriptionOptionsMetaBox extends FactoryMetaboxes000_FormMetabox {

		/**
		 * A visible title of the metabox.
		 *
		 * Inherited from the class FactoryMetabox.
		 * @link http://codex.wordpress.org/Function_Reference/add_meta_box
		 *
		 * @since 1.0.0
		 * @var string
		 */
		public $title;

		/**
		 * A prefix that will be used for names of input fields in the form.
		 *
		 * Inherited from the class FactoryFormMetabox.
		 *
		 * @since 1.0.0
		 * @var string
		 */
		public $scope = 'opanda';

		/**
		 * The priority within the context where the boxes should show ('high', 'core', 'default' or 'low').
		 *
		 * @link http://codex.wordpress.org/Function_Reference/add_meta_box
		 * Inherited from the class FactoryMetabox.
		 *
		 * @since 1.0.0
		 * @var string
		 */
		public $priority = 'core';

		public $cssClass = 'factory-bootstrap-000';

		public function __construct($plugin)
		{
			parent::__construct($plugin);

			$this->title = __('Subscription Options', 'bizpanda-email-locker');
		}

		/**
		 * Configures a metabox.
		 */
		public function configure($scripts, $styles)
		{

			$scripts->add(OPANDA_BIZPANDA_URL . '/assets/admin/js/libs/json2.js');

			if( OPanda_Items::isCurrentPremium() ) {
				$scripts->add(BIZPANDA_EMAIL_LOCKER_URL . '/admin/assets/js/custom-fields.010000.js');
			}

			$styles->add(BIZPANDA_EMAIL_LOCKER_URL . '/admin/assets/css/subscription-options.010000.css');
			$scripts->add(BIZPANDA_EMAIL_LOCKER_URL . '/admin/assets/js/subscription-options.010000.js');

			do_action('opanda_subscription_options_assets', $scripts, $styles);
		}

		/**
		 * Configures a form that will be inside the metabox.
		 *
		 * @see FactoryMetaboxes000_FormMetabox
		 * @since 1.0.0
		 *
		 * @param FactoryForms000_Form $form A form object to configure.
		 * @return void
		 */
		public function form($form)
		{

			$options = array();

			$options[] = array(
				'type' => 'html',
				'html' => array($this, 'showSubscriptionService')
			);

			require_once OPANDA_BIZPANDA_DIR . '/admin/includes/subscriptions.php';
			$serviceName = OPanda_SubscriptionServices::getCurrentName();
			$serviceInfo = OPanda_SubscriptionServices::getCurrentServiceInfo();

			if( 'database' !== $serviceName && 'none' !== $serviceName ) {

				$serviceName = OPanda_SubscriptionServices::getCurrentServiceInfo();
				$manualList = isset($serviceName['manualList'])
					? $serviceName['manualList']
					: false;

				if( $manualList ) {

					$options[] = array(
						'type' => 'textbox',
						'name' => 'subscribe_list',
						'title' => __('List', 'bizpanda-email-locker'),
						'hint' => __('Specify the list ID to add subscribers.', 'bizpanda-email-locker')
					);
				} else {

					$options[] = array(
						'type' => 'dropdown',
						'name' => 'subscribe_list',
						'data' => array(
							'ajax' => true,
							'url' => admin_url('admin-ajax.php'),
							'data' => array(
								'action' => 'opanda_get_subscrtiption_lists'
							)
						),
						'empty' => __('- empty -', 'bizpanda-email-locker'),
						'title' => __('List', 'bizpanda-email-locker'),
						'hint' => __('Select the list to add subscribers.', 'bizpanda-email-locker')
					);
				}
			}

			$modes = OPanda_SubscriptionServices::getCurrentOptinModes();

			$options[] = array(
				'type' => 'dropdown',
				'name' => 'subscribe_mode',
				'hasGroups' => false,
				'hasHints' => true,
				'data' => OPanda_SubscriptionServices::getCurrentOptinModes(true),
				'title' => __('Opt-In Mode', 'bizpanda-email-locker')
			);

			if( !isset($serviceInfo['transactional']) || !$serviceInfo['transactional'] ) {

				$choices = array(
					'service' => array(
						'value' => 'service',
						'title' => sprintf(__('Through %s', 'bizpanda-email-locker'), $serviceInfo['title']),
						'hint' => sprintf(__('%s takes care about sending confirmation emails.', 'bizpanda-email-locker'), $serviceInfo['title'])
					),
					'wordpress' => array(
						'value' => 'wordpress',
						'title' => __('Through Wordpress', 'bizpanda-email-locker'),
						'hint' => __('Wordpress and installed plugins take care about sending confirmation emails. By default Wordpress utilizes an email delivery service of your web host (usually not reliable). Fortunately there is plenty of reliable services you can integrate easily (<a href="https://wordpress.org/plugins/sendgrid-email-delivery-simplified/" target="_blank">SendGrid</a>, <a href="https://wordpress.org/plugins/mailin/" target="_blank">SendinBlue</a>, <a href="https://wordpress.org/plugins/wpmandrill/" target="_blank">Mandrill</a>, <a href="https://wordpress.org/plugins/postmark-approved-wordpress-plugin/" target="_blank">Postmark</a>, <a href="https://wordpress.org/plugins/mailjet-for-wordpress/" target="_blank">Mailjet</a>, <a href="https://wordpress.org/plugins/wp-ses/" target="_blank">Amazon SES</a> and others).', 'bizpanda-email-locker'),
					)
				);

				if( !isset($modes['quick']) ) {
					unset($choices['wordpress']);
				}

				if( !isset($modes['double-optin']) && !isset($modes['quick-double-optin']) ) {
					unset($choices['service']);
				}

				$selectItems = array();
				foreach($choices as $choice)
					$selectItems[] = $choice;

				$deliveryOption = array(
					'type' => 'dropdown',
					'name' => 'subscribe_delivery',
					'hasGroups' => false,
					'hasHints' => true,
					'data' => $selectItems,
					'title' => __('Send Confirmation', 'bizpanda-email-locker')
				);

				$options[] = array(
					'type' => 'div',
					'id' => 'opanda-delivery-options',
					'items' => array(
						$deliveryOption
					)
				);
			}

			$siteTitle = get_bloginfo('name');
			$siteDescription = get_bloginfo('description');

			$emailSubject = array(
				'type' => 'textbox',
				'name' => 'confirm_email_subject',
				'title' => __('Subject', 'bizpanda-email-locker'),
				'default' => __('Please confirm your email address', 'bizpanda-email-locker')
			);

			$emailBody = array(
				'type' => 'wp-editor',
				'name' => 'confirm_email_body',
				'title' => __('Text', 'bizpanda-email-locker'),
				'hint' => __('Use the shortcode <strong>[link]</strong> to insert the confirmation link.', 'bizpanda-email-locker'),
				'tinymce' => array(
					'height' => 120
				),
				'layout' => array(
					'hint-position' => 'left'
				),
				'default' => sprintf(__('To confirm your email address and unlock the content, please click the link below:<br />[link]<br/><br/>-<br/>%s<br/>%s', 'bizpanda-email-locker'), $siteTitle, $siteDescription)
			);

			$options[] = array(
				'type' => 'div',
				'id' => 'opanda-confirmation-email',
				'items' => array(
					array('type' => 'separator'),
					array('type' => 'html', 'html' => array($this, 'showConfirmationMessageHeader')),
					$emailSubject,
					$emailBody,
					array('type' => 'separator')
				)
			);

			$options[] = array(
				'type' => 'hidden',
				'name' => 'catch_leads',
				'default' => true
			);

			if( OPanda_Items::isCurrentPremium() ) {

				$options[] = array(
					'type' => 'dropdown',
					'way' => 'buttons',
					'name' => 'form_type',
					'data' => array(
						array('email-form', '<i class="fa fa-envelope-o"></i>' . __('Email Only', 'bizpanda-email-locker')),
						array('name-email-form', '<i class="fa fa-user"></i>' . __('Name & Email', 'bizpanda-email-locker')),
						array('custom-form', '<i class="fa fa-puzzle-piece"></i>' . __('Custom Form', 'bizpanda-email-locker'))
					),
					'title' => __('Form Fields', 'bizpanda-email-locker'),
					'hint' => __('Choose which fields the user has to fill to unlock your content.', 'bizpanda-email-locker'),
					'default' => 'email-form'
				);
			} else {

				$options[] = array(
					'type' => 'dropdown',
					'way' => 'buttons',
					'name' => 'form_type',
					'data' => array(
						array('email-form', '<i class="fa fa-envelope-o"></i>' . __('Email Only', 'bizpanda-email-locker')),
						array('name-email-form', '<i class="fa fa-user"></i>' . __('Name & Email', 'bizpanda-email-locker')),
						array(
							'custom-form',
							'<i class="fa fa-puzzle-piece"></i>' . __('Custom Form', 'bizpanda-email-locker'),
							sprintf(__('This option is available only in the <a href="%s" target="_blank">premium version</a> of the plugin (the transparency mode will be used in the free version)', 'bizpanda-email-locker'), opanda_get_premium_url(null, 'custom-fields'))
						)
					),
					'title' => __('Form Fields', 'bizpanda-email-locker'),
					'hint' => __('Choose which fields the user has to fill to unlock your content.', 'bizpanda-email-locker'),
					'default' => 'email-form'
				);
			}

			$emailFormOptions = array(
				'type' => 'div',
				'id' => 'opanda-email-form-options',
				'items' => array()
			);

			$emailFormOptions['items'][] = array(
				'type' => 'separator'
			);

			$emailFormOptions['items'][] = array(
				'type' => 'checkbox',
				'way' => 'buttons',
				'name' => 'subscribe_allow_social',
				'title' => __('Social Subscription', 'bizpanda-email-locker'),
				'hint' => __('Turn on to allow the subscription through social networks.', 'bizpanda-email-locker'),
				'default' => false
			);

			if( !onp_build('free') ) {

				if( BizPanda::hasPlugin('sociallocker') ) {

					$prepareItems = array(
						array(
							'type' => 'textbox',
							'name' => 'subscribe_social_text',
							'title' => __('Above Text', 'bizpanda-email-locker'),
							'hint' => __('The text above the buttons.', 'bizpanda-email-locker'),
							'default' => __('subscribe via your social profile by one click', 'bizpanda-email-locker')
						),
						array(
							'type' => 'list',
							'way' => 'checklist',
							'name' => 'subscribe_social_buttons',
							'data' => array(
								array('facebook', __('Facebook', 'bizpanda-email-locker')),
								array('google', __('Google', 'bizpanda-email-locker')),
								array('linkedin', __('LinkedIn', 'bizpanda-email-locker'))
							),
							'default' => 'facebook,google,linkedin',
							'errors' => array($this, 'getSocialErros'),
							'title' => __('Social Networks', 'bizpanda-email-locker'),
							'hint' => __('Mark available social networks.', 'bizpanda-email-locker')
						)
					);

					$prepareItems[1]['data'][] = array('vk', __('Vkontakte', 'bizpanda-email-locker'));
					$prepareItems[1]['default'] = 'facebook,google,vk';

					$emailFormOptions['items'][] = array(
						'type' => 'div',
						'id' => 'social-buttons-options',
						'items' => $prepareItems
					);
				} else {

					$emailFormOptions['items'][] = array(
						'type' => 'div',
						'id' => 'social-buttons-options',
						'items' => array(

							array(
								'type' => 'html',
								'html' => array($this, 'showSocialLockerNote')
							)
						)
					);
				}

				$options[] = $emailFormOptions;
			}

			if( OPanda_Items::isCurrentPremium() ) {

				$customFormOptions = array(
					'type' => 'div',
					'id' => 'opanda-custom-form-options',
					'items' => array()
				);

				$customFormOptions['items'][] = array(
					'type' => 'separator'
				);

				$customFormOptions['items'][] = array(
					'type' => 'html',
					'html' => array($this, 'showCustomFieldsEditor')
				);

				$customFormOptions['items'][] = array(
					'type' => 'hidden',
					'name' => 'fields'
				);

				$options[] = $customFormOptions;
			}

			$form->add($options);
		}

		public function showSubscriptionService()
		{

			$info = OPanda_SubscriptionServices::getCurrentServiceInfo();
			$serviceName = (empty($info))
				? 'none'
				: $info['name'];

			?>
			<div class="form-group">
				<label class="col-sm-2 control-label"></label>

				<div class="control-group controls col-sm-10">
					<?php if( 'database' === $serviceName ) { ?>
						<?php printf(__('The emails will be saved in the <a href="%s" target="_blank">local database</a> because you haven\'t selected a mailing service', 'bizpanda-email-locker'), opanda_get_subscribers_url()) ?>
					<?php } else { ?>
						<?php printf(__('You selected <strong>%s</strong> as your mailing service', 'bizpanda-email-locker'), $info['title']) ?>
					<?php } ?>

					(<a href="<?php echo opanda_get_settings_url('subscription') ?>" target="_blank"><?php _e('change', 'bizpanda-email-locker') ?></a>).
				</div>
			</div>
		<?php
		}

		public function showSocialLockerNote()
		{
			require_once OPANDA_BIZPANDA_DIR . '/admin/includes/plugins.php';
			$url = OPanda_Plugins::getPremiumUrl('sociallocker');

			?>
			<div class="form-group">
				<label class="col-sm-2 control-label"></label>

				<div class="control-group controls col-sm-10">
					<div class="alert alert-warning" style="margin-top: 0px;">
						<?php printf(__('To enable the social subscription option, please install the Social Locker plugin which provides social features. <a href="%s" target="_blank">Click here to learn more</a>.', 'bizpanda-email-locker'), $url) ?>
					</div>
				</div>
			</div>
		<?php
		}

		public function showFormTypes()
		{
			?>
			<div class="form-group">
				<label class="col-sm-2 control-label">
					<?php _e('Fields', 'bizpanda-email-locker') ?>
					<div class="help-block">
						<?php _e('<strong>Hint:</strong> drag & drop to reorder the fields.', 'bizpanda-email-locker') ?>
					</div>
				</label>
			</div>
		<?php
		}

		public function showConfirmationMessageHeader()
		{
			?>
			<div class="form-group">
				<label class="col-sm-2 control-label"></label>

				<div class="control-group controls col-sm-10">
					<?php printf(__('The confirmation email to send. You can change the sender email in the <a href="%s" target="_blank">global settings</a>.', 'bizpanda-email-locker'), opanda_get_settings_url('subscription')) ?>
				</div>
			</div>
		<?php
		}

		/**
		 * Shows the edtor to setup the custom fields.
		 */
		public function showCustomFieldsEditor()
		{
			$info = OPanda_SubscriptionServices::getCurrentServiceInfo();
			if( $info['name'] == 'database' )
				$info['title'] = __('Database', 'bizpanda-email-locker');

			$customFieldsNotice = sprintf(__('Choose how this field will work. Map it to one of custom fields in %s or use it as a helper element to decorate your form.', 'bizpanda-email-locker'), $info['title']);

			?>
			<script>
				if( !window.bizpanda ) {
					window.bizpanda = {};
				}
				if( !window.bizpanda.res ) {
					window.bizpanda.res = {};
				}

				window.bizpanda.res['service-title'] = "<?php echo $info['title'] ?>";
				window.bizpanda.res['unexpected-error'] = "<?php _e('Unexpected error occurred. Please try to refresh this page.', 'bizpanda-email-locker'); ?>";
				window.bizpanda.res['email-field-hint'] = "<?php _e('The value from this field will be used as an email address. This field is always required.', 'bizpanda-email-locker'); ?>";
				window.bizpanda.res['email-field-placeholder'] = "<?php echo get_option('opanda_res_misc_enter_your_email', __('enter your email', 'bizpanda-email-locker')); ?>";
				window.bizpanda.res['fullname-field-hint'] = "<?php _e('The value from this field will be splited (if possible) into the two parts: First and Last Namey. Use this field instead of creating a separate field for each name.', 'bizpanda-email-locker'); ?>";
				window.bizpanda.res['fullname-field-placeholder'] = "<?php echo get_option('opanda_res_misc_enter_your_name', __('enter your name', 'bizpanda-email-locker')); ?>";
				window.bizpanda.res['loading-state'] = "<?php _e('[ - loading - ]', 'bizpanda-email-locker'); ?>";
				window.bizpanda.res['error-state'] = "<?php _e('[ - error - ]', 'bizpanda-email-locker'); ?>";
				window.bizpanda.res['phone-field-placeholder'] = "<?php _e('enter your phone number', 'bizpanda-email-locker'); ?>";
				window.bizpanda.res['birthday-field-placeholder'] = "<?php _e('enter your birthday', 'bizpanda-email-locker'); ?>";
				window.bizpanda.res['checkbox-default-description'] = "<?php _e('Write here why the user has to mark it.', 'bizpanda-email-locker'); ?>";
				window.bizpanda.res['label-default-text'] = "<?php _e('Text Label:', 'bizpanda-email-locker'); ?>";
				window.bizpanda.res['unsupported'] = "<?php _e('[ - unsupported- ]', 'bizpanda-email-locker'); ?>";
				window.bizpanda.res['unsupported-type'] = "<?php _e('The custom field of this type is not supported currently with the plugin.', 'bizpanda-email-locker'); ?>";
			</script>
			<div class="form-group">
				<label class="col-sm-2 control-label">
					<?php _e('Fields', 'bizpanda-email-locker') ?>
					<div class="help-block">
						<?php _e('<strong>Hint:</strong> drag & drop to reorder the fields.', 'bizpanda-email-locker') ?>
					</div>
				</label>

				<div class="control-group controls col-sm-10" id="opanda-fields-editor">
					<div class="opanda-error" style="display: none;">
						<i class="fa fa-exclamation-triangle"></i>
						<span class="opanda-error-text"></span>
					</div>
					<div class='table-wrap factory-fontawesome-000'>
						<table class="table opanda-table">
							<thead>
							<tr>
								<th class="opanda-gray opanda-drag"></th>
								<th class="opanda-min opanda-icon">
									<span><?php _e('Icon', 'bizpanda-email-locker') ?></span>
								</th>
								<th class="opanda-mapping">
									<span><?php _e('Destination', 'bizpanda-email-locker') ?></span>
									<i class="opanda-th-hint" data-popup-hint="#opanda-mapping-hint"></i>

									<div id="opanda-mapping-hint" class="opanda-popup-hint">
										<?php echo $customFieldsNotice ?>
									</div>
								</th>
								<th class="opanda-label">
									<span><?php _e('Field Label', 'bizpanda-email-locker') ?></span>
									<i class="opanda-th-hint" data-popup-hint="#opanda-label-name"></i>

									<div id="opanda-label-name" class="opanda-popup-hint">
										<?php _e('The Field Label is an internal name of the field used in reports and lead details.', 'bizpanda-email-locker') ?>
									</div>
								</th>
								<th class="opanda-min opanda-type">
									<span><?php _e('Control Type', 'bizpanda-email-locker') ?></span>
								</th>
								<th class="opanda-min opanda-required">
									<span><?php _e('Required?', 'bizpanda-email-locker') ?></span>
								</th>
								<th class="opanda-control opanda-min"></th>
							</tr>
							</thead>
							<tbody>
							<tr class="opanda-item opanda-template">
								<td class="opanda-gray opanda-drag"></td>
								<td class="opanda-icon">
									<select class="form-control opanda-icon-input"></select>
								</td>
								<td class="opanda-mapping">
									<select class="form-control opanda-mapping-input opanda-lazy-select"></select>
								</td>
								<td class="opanda-label">
									<input type="text" value="" class="form-control opanda-label-input"/>
								</td>
								<td class="opanda-type">
									<select class="form-control opanda-type-input opanda-lazy-select"></select>
								</td>
								<td class="opanda-required">
									<input type="checkbox" class="opanda-required-input"/>
								</td>
								<td class="opanda-control">
									<a href="#" class="btn btn-default opanda-configure"><i class="fa fa-cog"></i></a>
									<a href="#" class="btn btn-default opanda-remove"><i class="fa fa-times"></i></a>
								</td>
							</tr>
							</tbody>
						</table>
					</div>
					<div class='opanda-options-templates'>
						<!-- Text Options -->
						<div class="opanda-options opanda-text-options">
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php _e('Icon Position', 'bizpanda-email-locker') ?></label>

								<div class="control-group col-sm-10">
									<select class='form-control opanda-icon-position-input'>
										<option value="right"><?php _e('Right', 'bizpanda-email-locker') ?></option>
										<option value="left"><?php _e('Left', 'bizpanda-email-locker') ?></option>
										<option value="none"><?php _e('None', 'bizpanda-email-locker') ?></option>
										<select>
											<div class='help-block'>
												<?php _e('The position of the icon.', 'bizpanda-email-locker') ?>
											</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php _e('Title', 'bizpanda-email-locker') ?></label>

								<div class="control-group col-sm-10">
									<input type='text' class='form-control opanda-title-input'/>

									<div class='help-block'>
										<?php _e('Optional. The title appears above the field.', 'bizpanda-email-locker') ?>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php _e('Placeholder', 'bizpanda-email-locker') ?></label>

								<div class="control-group col-sm-10">
									<input type='text' class='form-control opanda-placeholder-input'/>

									<div class='help-block'>
										<?php _e('Optional. The placeholder appears in the textbox when it\'s empty.', 'bizpanda-email-locker') ?>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php _e('Mask', 'bizpanda-email-locker') ?></label>

								<div class="control-group col-sm-10">
									<input type='text' class='form-control opanda-mask-input'/>
									<input type='hidden' class='form-control opanda-mask-placeholder-input'/>

									<div class='help-block'>
										<?php printf(__('Optional. <a href="%s" target="_blank">Set the mask</a> if you need the user to fill this field in the certain format.', 'bizpanda-email-locker'), 'http://support.onepress-media.com/input-masks/') ?>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php _e('Is Password', 'bizpanda-email-locker') ?></label>

								<div class="control-group col-sm-10">
									<div class="checkbox">
										<label>
											<input type="checkbox" class='opanda-is-password-input'>
											<?php _e('Check to hide content of this field behind "*".', 'bizpanda-email-locker'); ?>
										</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group">
								<label class="col-sm-2 control-label"> </label>

								<div class="control-group col-sm-10">
									<a href='#' class='btn btn-default opanda-hide'><?php _e('Hide Options', 'bizpanda-email-locker') ?></a>
								</div>
							</div>
						</div>
						<!-- Integer  Options -->
						<div class="opanda-options opanda-integer-options">
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php _e('Icon Position', 'bizpanda-email-locker') ?></label>

								<div class="control-group col-sm-10">
									<select class='form-control opanda-icon-position-input'>
										<option value="right"><?php _e('Right', 'bizpanda-email-locker') ?></option>
										<option value="left"><?php _e('Left', 'bizpanda-email-locker') ?></option>
										<option value="none"><?php _e('None', 'bizpanda-email-locker') ?></option>
										<select>
											<div class='help-block'>
												<?php _e('The position of the icon.', 'bizpanda-email-locker') ?>
											</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php _e('Title', 'bizpanda-email-locker') ?></label>

								<div class="control-group col-sm-10">
									<input type='text' class='form-control opanda-title-input'/>

									<div class='help-block'>
										<?php _e('Optional. The title appears above the field.', 'bizpanda-email-locker') ?>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php _e('Placeholder', 'bizpanda-email-locker') ?></label>

								<div class="control-group col-sm-10">
									<input type='text' class='form-control opanda-placeholder-input'/>

									<div class='help-block'>
										<?php _e('Optional. The placeholder appears in the textbox when it\'s empty.', 'bizpanda-email-locker') ?>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php _e('Min Value', 'bizpanda-email-locker') ?></label>

								<div class="control-group col-sm-10">
									<input type='text' class='form-control opanda-min-input'/>

									<div class='help-block'>
										<?php _e('Optional. The minimum allowed value.', 'bizpanda-email-locker') ?>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php _e('Max Value', 'bizpanda-email-locker') ?></label>

								<div class="control-group col-sm-10">
									<input type='text' class='form-control opanda-max-input'/>

									<div class='help-block'>
										<?php _e('Optional. The maximum allowed value.', 'bizpanda-email-locker') ?>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group">
								<label class="col-sm-2 control-label"> </label>

								<div class="control-group col-sm-10">
									<a href='#' class='btn btn-default opanda-hide'><?php _e('Hide Options', 'bizpanda-email-locker') ?></a>
								</div>
							</div>
						</div>
						<!-- Dropdown Options -->
						<div class="opanda-options opanda-dropdown-options">
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php _e('Title', 'bizpanda-email-locker') ?></label>

								<div class="control-group col-sm-10">
									<input type='text' class='form-control opanda-title-input'/>

									<div class='help-block'>
										<?php _e('Optional. The title appears above the field.', 'bizpanda-email-locker') ?>
									</div>
								</div>
							</div>
							<div class="form-group opanda-can-notice" style="display: none;">
								<label class="col-sm-2 control-label"></label>

								<div class="control-group col-sm-10">
									<div class="alert alert-warning"></div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php _e('Choices', 'bizpanda-email-locker') ?></label>

								<div class="control-group col-sm-10 opanda-choices-editor">
									<div class="opanda-choices-holder">
									</div>
									<div class="opanda-choice-item opanda-choice-item-template">
										<input type='text' class='form-control opanda-choise-value-input' placeholder="<?php _e('Option Title') ?>"/>
										<a href="#" class="btn btn-default opanda-choice-remove"><i class="fa fa-times"></i></a>
									</div>
									<div class="opanda-choices-controls">
										<a href="#" class="btn btn-default opanda-add-choice">
											<i class="fa fa-plus"></i>
											<?php _e('Add Choice', 'bizpanda-email-locker') ?>
										</a>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group">
								<label class="col-sm-2 control-label"> </label>

								<div class="control-group col-sm-10">
									<a href='#' class='btn btn-default opanda-hide'><?php _e('Hide Options', 'bizpanda-email-locker') ?></a>
								</div>
							</div>
						</div>
						<!-- Hidden Options -->
						<div class="opanda-options opanda-hidden-options">
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php _e('Value', 'bizpanda-email-locker') ?></label>

								<div class="control-group col-sm-10">
									<input type='text' class='form-control opanda-value-input'/>

									<div class='help-block'>
										<?php _e('This value will be saved when submitting the form.', 'bizpanda-email-locker') ?>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group">
								<label class="col-sm-2 control-label"> </label>

								<div class="control-group col-sm-10">
									<a href='#' class='btn btn-default opanda-hide'><?php _e('Hide Options', 'bizpanda-email-locker') ?></a>
								</div>
							</div>
						</div>
						<!-- Checkbox Options -->
						<div class="opanda-options opanda-checkbox-options">
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php _e('Description', 'bizpanda-email-locker') ?></label>

								<div class="control-group col-sm-10">
									<input type='text' class='form-control opanda-description-input'/>

									<div class='help-block'>
										<?php _e('The description appears next to the checkbox.', 'bizpanda-email-locker') ?>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php _e('Marked By Default', 'bizpanda-email-locker') ?></label>

								<div class="control-group col-sm-10">
									<div class="checkbox">
										<label>
											<input type="checkbox" class='opanda-marked-by-default-input'>
											<?php _e('Check to make this checkbox marked by default.', 'bizpanda-email-locker'); ?>
										</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php _e('Marked Value', 'bizpanda-email-locker') ?></label>

								<div class="control-group col-sm-10">
									<input type='text' class='form-control opanda-marked-value-input'/>

									<div class='help-block'>
										<?php _e('This value will be saved if the checkbox is marked.', 'bizpanda-email-locker') ?>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php _e('Unmarked Value', 'bizpanda-email-locker') ?></label>

								<div class="control-group col-sm-10">
									<input type='text' class='form-control opanda-unmarked-value-input'/>

									<div class='help-block'>
										<?php _e('This value will be saved if the checkbox is not marked.', 'bizpanda-email-locker') ?>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group">
								<label class="col-sm-2 control-label"> </label>

								<div class="control-group col-sm-10">
									<a href='#' class='btn btn-default opanda-hide'><?php _e('Hide Options', 'bizpanda-email-locker') ?></a>
								</div>
							</div>
						</div>
						<!-- Seprator Options -->
						<div class="opanda-options opanda-separator-options">
							<?php _e('The separator does not have any options to configure.', 'bizpanda-email-locker') ?>
						</div>
						<!-- Label Options -->
						<div class="opanda-options opanda-label-options">
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php _e('Text', 'bizpanda-email-locker') ?></label>

								<div class="control-group col-sm-10">
									<input type='text' class='form-control opanda-text-input'/>

									<div class='help-block'>
										<?php _e('Enter text for the label to display.', 'bizpanda-email-locker') ?>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group">
								<label class="col-sm-2 control-label"> </label>

								<div class="control-group col-sm-10">
									<a href='#' class='btn btn-default opanda-hide'><?php _e('Hide Options', 'bizpanda-email-locker') ?></a>
								</div>
							</div>
						</div>
						<!-- Html Options -->
						<div class="opanda-options opanda-html-options">
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php _e('Html', 'bizpanda-email-locker') ?></label>

								<div class="control-group col-sm-10">
									<textarea class='form-control opanda-html-input'></textarea>

									<div class='help-block'>
										<?php _e('Paste here html code to display.', 'bizpanda-email-locker') ?>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group">
								<label class="col-sm-2 control-label"> </label>

								<div class="control-group col-sm-10">
									<a href='#' class='btn btn-default opanda-hide'><?php _e('Hide Options', 'bizpanda-email-locker') ?></a>
								</div>
							</div>
						</div>
						<!-- Unsupported Options -->
						<div class="opanda-options opanda-unsupported-options">
							<?php _e('Sorry, it seems we plugin does not support this field type.', 'bizpanda-email-locker') ?>
						</div>
					</div>
					<div class="opanda-controls">
						<a href="#" class="btn btn-default opanda-add-field">
							<i class="fa fa-plus"></i>
							<?php _e('Add Field', 'bizpanda-email-locker') ?>
						</a>
					</div>
				</div>
			</div>
		<?php
		}

		public function beforeForm(FactoryForms000_Form $form)
		{
			echo '<div class="factory-fontawesome-000">';
		}

		public function afterForm(FactoryForms000_Form $form)
		{
			echo '</div>';
		}

		/**
		 * Returns errors for social options.
		 *
		 * @since 1.0.0
		 * @return string
		 */
		public function getSocialErros()
		{

			return array(
				'facebook' => $this->getFacebookErrors(),
				'google' => $this->getGoogleErrors(),
				'linkedin' => $this->getLinkedInErrors(),
			);
		}

		/**
		 * Returns errors of the Facebook Connect button.
		 *
		 * @since 1.0.0
		 * @return string
		 */
		public function getFacebookErrors()
		{

			$appId = get_option('opanda_facebook_appid', null);
			if( empty($appId) || '117100935120196' === $appId ) {
				return sprintf(__('You need to register a Facebook App for your website. Please <a href="%s" target="_blank">click here</a> to learn more.', 'bizpanda-email-locker'), admin_url('admin.php?page=how-to-use-' . $this->plugin->pluginName . '&onp_sl_page=facebook-app'));
			}

			return false;
		}

		/**
		 * Returns errors of the Facebook Connect button.
		 *
		 * @since 1.0.0
		 * @return string
		 */
		public function getGoogleErrors()
		{

			$clientId = get_option('opanda_google_client_id', null);
			if( empty($clientId) ) {
				return sprintf(__('You need to get a Google Client ID for your website. Please <a href="%s" target="_blank">click here</a> to learn more.', 'bizpanda-email-locker'), admin_url('admin.php?page=how-to-use-' . $this->plugin->pluginName . '&onp_sl_page=google-client-id'));
			}

			return false;
		}

		/**
		 * Returns errors of the Facebook Connect button.
		 *
		 * @since 1.0.0
		 * @return string
		 */
		public function getLinkedInErrors()
		{

			$clientId = get_option('opanda_linkedin_client_id', null);
			$clientSecret = get_option('opanda_linkedin_client_secret', null);

			if( empty($clientId) || empty($clientSecret) ) {
				return sprintf(__('You need to get LinkedIn Client ID and Secret for your website. Please <a href="%s" target="_blank">click here</a> to learn more.', 'bizpanda-email-locker'), admin_url('admin.php?page=how-to-use-' . $this->plugin->pluginName . '&onp_sl_page=linkedin-api-key'));
			}

			return false;
		}

		/**
		 * Returns errors of the Vkontakte Connect button.
		 *
		 * @since 1.0.0
		 * @return string
		 */
		public function getVkErrors()
		{

			$appId = get_option('opanda_vk_appid', null);
			$appSecret = get_option('opanda_vk_app_secret', null);

			if( empty($appId) || empty($appSecret) ) {
				return array(
					'text' => sprintf(__('You need to get a Vk app id and protected key for your website. Please <a href="%s" target="_blank">click here</a> to learn more.', 'bizpanda-email-locker'), admin_url('admin.php?page=settings-bizpanda&opanda_screen=social'))
				);
			}

			return false;
		}


		/**
		 * Extra custom actions after the form is saved.
		 */
		public function onSavingForm($post_id)
		{

			$fields = stripslashes($_POST['opanda_fields']);
			$formType = $_POST['opanda_form_type'];

			$hasMask = (('custom-form' === $formType) && (preg_match('/\"mask\":\"[^\"]+\"/i', $fields) || preg_match('/birthday/i', $fields)));
			update_post_meta($post_id, 'opanda_has_mask', $hasMask);

			$hasDate = (('custom-form' === $formType) && (preg_match('/\"type\"\:\"date\"/i', $fields)));
			update_post_meta($post_id, 'opanda_has_date', $hasDate);

			$hasFontawesome = (('custom-form' === $formType) && (preg_match('/\"icon\"\:\"[^\"]+\"/i', $fields)));
			update_post_meta($post_id, 'opanda_has_fontawesome', $hasFontawesome);
		}
	}

	FactoryMetaboxes000::register('OPanda_SubscriptionOptionsMetaBox', $bizpanda);
/*@mix:place*/