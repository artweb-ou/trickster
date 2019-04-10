<div class="submenu_block submenu_header{if isset($className)} {$className}{/if}">
	{if $element->popup}
		<script>
			/*<![CDATA[*/
			window.subMenusInfo = window.subMenusInfo || {ldelim}{rdelim};
			window.subMenusInfo['{$element->id}'] = {$element->getMenusInfo()|json_encode};
			/*]]>*/
		</script>
	{/if}
	<nav class='submenu_content'>
		<div class='submenu_items_block'>
			{include file=$theme->template("subMenuList.items.tpl") level=1 levels=$element->levels usePopup=$element->popup subMenus=$element->getSubMenuList() verticalPopup=true}
		</div>
	</nav>
</div>