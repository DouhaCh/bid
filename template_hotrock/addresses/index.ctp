<?php
$html->addCrumb(__('Dash Board',true), '/users');
$html->addCrumb(__('My Addresses',true), '/addresses');
echo $this->element('crumb_user');
?>

<?php foreach($address as $name => $address) : ?>
	<h1><?php echo $name; ?> Address</h1>
	<?php if(!empty($address)) : ?>
		<table class="results" cellpadding="0" cellspacing="0">
		<tr>
				<th><?php __('Name');?></th>
				<th><?php __('Address');?></th>
				<th><?php __('Suburb / Town');?></th>
				<th><?php __('City / State / County');?></th>
				<th><?php __('Postcode');?></th>
				<th><?php __('Country');?></th>
				<th><?php __('Phone Number');?></th>
				<th class="actions"><?php __('Options');?></th>
			</tr>

		<tr>
			<td><?php echo $address['Address']['name']; ?></td>
			<td><?php echo $address['Address']['address_1']; ?><?php if(!empty($address['Address']['address_2'])) : ?>, <?php echo $address['Address']['address_2']; ?><?php endif; ?></td>
			<td><?php if(!empty($address['Address']['suburb'])) : ?><?php echo $address['Address']['suburb']; ?><?php else: ?>n/a<?php endif; ?></td>
			<td><?php echo $address['Address']['city']; ?></td>
			<td><?php echo $address['Address']['postcode']; ?></td>
			<td><?php echo $address['Country']['name']; ?></td>
			<td><?php if(!empty($address['Address']['phone'])) : ?><?php echo $address['Address']['phone']; ?><?php else: ?>n/a<?php endif; ?></td>
			<td><a href="/addresses/edit/<?php echo $name; ?>">Edit</a></td>
		</tr>
		</table>
	<?php else: ?>
		<p><a href="/addresses/add/<?php echo $name; ?>"><?php echo sprintf(__('Add a %s address', true), $name); ?></a></p>
	<?php endif; ?>
<?php endforeach; ?>