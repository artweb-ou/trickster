{if $element->hasActualStructureInfo()}
	<div class='controls_block content_list_controls'>
		{include file=$theme->template('block.newelement.tpl') allowedTypes=array("orderField") buttonId="addNewProduct"}
	</div>
{/if}
{include file=$theme->template('shared.contentTable.tpl') contentList=$currentElement->getOrderFields()}
