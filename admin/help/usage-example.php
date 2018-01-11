<?php

	global $bizpanda;

?>

<div class="onp-help-section">
	<h1><?php _e('Quick Start Guide', 'bizpanda-email-locker'); ?></h1>

	<p>
		<?php _e('To pick out the content which should be locked, you can use special shortcodes. During installation, the plugin created for you the shortcode <span class="onp-mark onp-mark-gray onp-mark-stricked onp-code">[emaillocker][/emaillocker]</span> named <strong>Email Locker</strong>.', 'bizpanda-email-locker'); ?>
	</p>

	<p class='onp-note'>
		<?php _e('<strong>Note:</strong> You can create more shortcodes at any time for whatever you need them for. For instance, you could create one for locking video players or another one for locking download links.', 'bizpanda-email-locker'); ?>
	</p>

	<p>
		<?php _e('But before you start, you need to choose your email marketing service which will be used to store contacts of your future subscribers.', 'bizpanda-email-locker') ?>
	</p>

	<p>
		<?php _e('The plugin supports all the major mailing services, including <a href="http://mailchimp.com">MailChimp</a>, <a href="http://aweber.com">Aweber</a>, <a href="http://getresponse.com">GetResponse</a>.', 'bizpanda-email-locker') ?>
	</p>

	<p>
		<?php _e('Concurrently the Email Locker saves contacts of all the attracted subscribers locally in your Wordpress database. You can export them later at any time.', 'bizpanda-email-locker') ?>
	</p>
</div>
<div class="onp-help-section">
	<h2>1. <?php _e('Choose your email service', 'bizpanda-email-locker'); ?></h2>

	<p>
		<?php _e('To configure your email marketing service, open the screen <strong>Subscription Options</strong>:', 'bizpanda-email-locker') ?>
		<br/>
		<i><?php printf(__('Opt-In Panda > Global Settings -> <a href="%s">Subscription Options</a>', 'bizpanda-email-locker'), admin_url('admin.php?page=settings-' . $bizpanda->pluginName . '&opanda_screen=subscription&action=index')) ?></i>
	</p>

	<p>
		<?php _e('Select your email service or select "None" to store the contacts locally only in your Wordpress database.', 'bizpanda-email-locker') ?>
	</p>

	<p>
		<?php _e('Now let\'s examine how to use the default shortcode <strong>Email Locker</strong>.', 'bizpanda-email-locker'); ?>
	</p>
</div>
<div class="onp-help-section">
	<h2>2. <?php _e('Open the editor', 'bizpanda-email-locker'); ?></h2>

	<p><?php printf(__('In admin menu, select Opt-In Panda -> <a href="%s">All Lockers</a>.', 'bizpanda-email-locker'), admin_url('edit.php?post_type=opanda-item')); ?></p>

	<p><?php _e('Then click on the shortcode titled <strong>Email Locker</strong> to open its editor:', 'bizpanda-email-locker'); ?></p>

	<p class='onp-img'>
		<img src='<?php echo 'https://cconp.s3.amazonaws.com/bizpanda/email-locker/panda-items.png' ?>'/>
	</p>
</div>
<div class="onp-help-section">
	<h2>3. <?php _e('Configure the locker', 'bizpanda-email-locker'); ?></h2>

	<p>
		1) <?php _e('Set a clear title that attracts attention or creates a call to action (see the example below).', 'bizpanda-email-locker'); ?></p>

	<p>
		2) <?php _e('Describe what the visitor will get after they unlock the content. This is very important, as visitors need to be aware of what they are getting. And please, only promise things you can deliver.', 'bizpanda-email-locker'); ?></p>

	<p>
		3) <?php _e('Specify the button text related with your call-to-action and if needed the text below the button.', 'bizpanda-email-locker'); ?></p>

	<p>4) <?php _e('Choose one of the available themes for your locker.', 'bizpanda-email-locker'); ?></p>

	<p>
		5) <?php _e('Set the Overlay Mode. We recommend to use the Blurring Mode as the most attention-grabbing mode.', 'bizpanda-email-locker'); ?></p>
	</p>
	<p class='onp-img'>
		<img src='<?php echo 'https://cconp.s3.amazonaws.com/bizpanda/email-locker/basic-options.png' ?>'/>
	</p>

	<p>
		6) <?php _e('If you have configured the Email Locker to use one of the third party email marketing services, select the list where you would like to add new subscribers.', 'bizpanda-email-locker'); ?>
	</p>

	<p>
		7) <?php _e('Set one of the available opt-in modes:', 'bizpanda-email-locker'); ?>
	</p>
	<table class="table table-condensed">
		<thead>
		<tr>
			<th style="white-space: nowrap"><?php _e('Mode Name', 'bizpanda-email-locker'); ?></th>
			<th style="padding-left: 20px;"><?php _e('Description', 'bizpanda-email-locker'); ?></th>
		</tr>
		</thead>
		<tbody>
		<tr>
			<td style="white-space: nowrap"><?php _e('Single Opt-In', 'bizpanda-email-locker'); ?></td>
			<td style="padding-left: 20px;"><?php _e('Unlocks the content immediately after the user clicks the button to subscribe. Doesn\'t send the confirmation email.', 'bizpanda-email-locker'); ?></td>
		</tr>
		<tr>
			<td style="white-space: nowrap"><?php _e('Lazy Double Opt-In', 'bizpanda-email-locker'); ?></td>
			<td style="padding-left: 20px;"><?php _e('Unlocks the content immediately after the user clicks the button as well as the Single Opt-In Mode but also sends the confirmation email (double opt-in).', 'bizpanda-email-locker'); ?></td>
		</tr>
		<tr>
			<td style="white-space: nowrap">
				<strong><?php _e('Full Double Opt-In', 'bizpanda-email-locker'); ?></strong><br/>
				<i><?php _e('(recommended)', 'bizpanda-email-locker'); ?></i>
			</td>
			<td style="padding-left: 20px;"><?php _e('After the user clicks the button, sends the confirmation email and waits until the user confirms one\'s subscription. Then, unlocks the content.', 'bizpanda-email-locker'); ?></td>
		</tr>
		</tbody>
	</table>
	<p>
		8) <?php _e('Decide do you really need to know a name of the subscriber. Remember less you ask, more opt-ins you get.', 'bizpanda-email-locker'); ?>
	</p>

	<p>
		9) <?php _e('For some users social networks is a more preferred way to subscribe, as a result, it may increase the conversion of your locker. Play around with the subscription through social networks later. Now you can skip it.', 'bizpanda-email-locker'); ?>
	</p>

	<p>
		<?php _e('The subscription through social networks works the same as through the form. Just an email address and name of the user is recivied from the social network.', 'bizpanda-email-locker'); ?>
	</p>

	<p class='onp-img'>
		<img src='<?php echo 'https://cconp.s3.amazonaws.com/bizpanda/email-locker/subscription-options.png' ?>'/>
	</p>

	<p>
		<?php printf(__('The page <a href="%s">Stats & Reports</a> will help you to correct your locker after collecting the first statistical data.', 'bizpanda-email-locker'), admin_url('edit.php?post_type=opanda-item&page=stats-' . $bizpanda->pluginName)); ?>
	</p>

	<?php if( !onp_build('free') ) { ?>

		<p class='onp-note'>
			<?php _e('On the right sidebars, there are some additional options which can help you to adjust the locker to your site audience. Try to use them by yourself later.', 'bizpanda-email-locker'); ?>
		</p>

	<?php } ?>

	<p>
		<?php _e('Congratulations! The locker is ready to use.', 'bizpanda-email-locker'); ?>
	</p>
</div>
<div class="onp-help-section">
	<h2>3. <?php _e('Place the locker shortcode', 'bizpanda-email-locker'); ?></h2>

	<p>
		<?php _e('Decide what content you would like to lock. It might be:', 'bizpanda-email-locker'); ?>
	<ul>
		<li><?php _e('A download link (for instance, a free graphic, an audio file, video resources, or a printable pdf of your article).', 'bizpanda-email-locker'); ?></li>
		<li><?php printf(__('A promo code (for instance, a %s off discount, if the visitor shares your promo page).', 'bizpanda-email-locker'), '10%'); ?></li>
		<li><?php _e('The end of your article (for instance, you might show the beginning of the article to gain interest, but hide the ending).', 'bizpanda-email-locker'); ?></li>
	</ul>
	<?php _e('Basically, you can hide any content that would be important for visitors who are visiting your site.', 'bizpanda-email-locker'); ?>
	</p>
	<p>
		<?php _e('However, <strong>you should never</strong>:', 'bizpanda-email-locker'); ?>
	<ul>
		<li>
			<?php _e('Lock all of your content, posts or pages.', 'bizpanda-email-locker'); ?>
		</li>
		<li>
			<?php _e('Lock boring content or content that is not interesting.', 'bizpanda-email-locker'); ?>
		</li>
	</ul>
	</p>
	<p>
		<?php _e('In other words, don’t try to trick your visitors.', 'bizpanda-email-locker'); ?>
	</p>

	<p>
		<?php _e('Open the post editor for the post where you want to put the locker. Wrap the content you want to lock within the locker shortcode. For example: <span class="onp-mark onp-mark-gray onp-mark-stricked onp-code">[emaillocker] Locked Content Goes Here [/emaillocker]</span>:', 'bizpanda-email-locker'); ?>
	</p>

	<p class='onp-img'>
		<img src='<?php echo 'https://cconp.s3.amazonaws.com/bizpanda/email-locker/shortcode.png' ?>'/>
	</p>

	<p>
		<?php _e('That’s it! Save your post and see the locker on your site! ', 'bizpanda-email-locker'); ?>
	</p>

	<p class='onp-img'>
		<img src='<?php echo 'https://cconp.s3.amazonaws.com/bizpanda/email-locker/emaillocker.png' ?>'/>
	</p>
</div>