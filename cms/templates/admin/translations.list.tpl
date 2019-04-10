<div class="content_list_block">
	{include file=$theme->template('translations.importcontrols.tpl')}
	<form class="content_list_form" action="{$currentElement->getFormActionURL()}" method="post" enctype="multipart/form-data">
		<div class='controls_block content_list_controls'>
			<input type="hidden" class="content_list_form_id" value="{$rootElement->id}" name="id" />
			<input type="hidden" class="content_list_form_elementid" value="{$currentElement->id}" />
			<input type="hidden" class="content_list_form_action" value="deleteElements" name="action" />

			{include file=$theme->template('block.buttons.tpl') allowedTypes=$currentElement->getAllowedChildStructureTypes()}

			{if isset($currentElementPrivileges.export)}
				<a class='button export' href="{$currentElement->URL}id:{$currentElement->id}/action:export/">{translations name='label.export'}</a>
			{/if}
		</div>
		{include file=$theme->template('shared.contentTable.tpl')}
	</form>

</div>