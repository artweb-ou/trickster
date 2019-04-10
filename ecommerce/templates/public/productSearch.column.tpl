{if $element->canBeDisplayed()}
	{assign 'searchBaseElement' $element->getSearchBaseElement()}
	{if $element->title}
		{capture assign="moduleTitle"}
			{$element->title}
		{/capture}
	{/if}
	{capture assign="moduleContent"}
		<script>
			/*<![CDATA[*/
			{if $element->canActLikeFilter()}
				window.productSearchBaseUrl = '{$searchBaseElement->URL}';
			{else}
				window.productSearchBaseUrl = '{$searchBaseElement->URL}id:{$searchBaseElement->id}/action:search/';
			{/if}
			{if $catalogueElement = $element->getCatalogue()}
			window.productSearchCatalogueUrl = '{$catalogueElement->URL}';
			{/if}
			window.categoriesUrls = window.categoriesUrls || {ldelim}{rdelim};
			{if $currentElement->type == 'category'}
				window.categoriesUrls[{$currentElement->id}] = '{$currentElement->URL}';
			{/if}
			/*]]>*/
		</script>

		<div class="productsearch_categories">
			{foreach $element->getFiltersByType('category') as $filter}
					{include $theme->template('productSearch.dropdownfilter.tpl')}
					<script>
						/*<![CDATA[*/
						{foreach $filter->getOptionsInfo() as $optionInfo}
							window.categoriesUrls[{$optionInfo.id}] = '{$optionInfo.url}';
						{/foreach}
						/*]]>*/
					</script>
			{/foreach}
		</div>

		{foreach $element->getFiltersByType('brand') as $filter}
			{if $filter->isRelevant()}
				{include $theme->template('productSearch.dropdownfilter.tpl')}
			{/if}
		{/foreach}

		{foreach $element->getFiltersByType('parameter') as $filter}
			{if $filter->isRelevant()}
				{if $element->checkboxesForParameters}
					{include $theme->template('productSearch.checkboxesfilter.tpl')}
				{else}
					{include $theme->template('productSearch.dropdownfilter.tpl')}
				{/if}
			{/if}
		{/foreach}

		{foreach $element->getFiltersByType('discount') as $filter}
			{if $filter->isRelevant()}
				{include $theme->template('productSearch.dropdownfilter.tpl')}
			{/if}
		{/foreach}

		{foreach $element->getFiltersByType('availability') as $filter}
			{if $filter->isRelevant()}
				{include $theme->template('productSearch.dropdownfilter.tpl')}
			{/if}
		{/foreach}

		{foreach $element->getFiltersByType('price') as $filter}
			{if $filter->isRelevant()}
				{if $element->pricePresets}
					{include $theme->template('productSearch.dropdownfilter.tpl')}
				{else}
					{include $theme->template('productSearch.pricefilter.tpl')}
				{/if}
			{/if}
		{/foreach}

		{if $element->canActLikeFilter() || !$element->pageDependent}
			{if $element->sortingEnabled}
				<div class="productsearch_field">
					<select class="productsearch_sortselect dropdown_placeholder">
						{foreach $element->getSortParameters() as $sortParameter}
							<option value='{$sortParameter.value}'{if $controller->getParameter('sort') == $sortParameter.value} selected="selected"{/if}>
								{$sortParameter.title}
							</option>
						{/foreach}
					</select>
				</div>
			{/if}
		{/if}
		<div class="productsearch_reset button"><span class="button_text">{translations name="productsearch.reset"}</span></div>
	{/capture}
	{assign moduleTitleClass "productsearch_title"}
	{assign moduleClass "productsearch"}
	{assign moduleContentClass "productsearch_content"}
	{include file=$theme->template("component.columnmodule.tpl")}
{/if}