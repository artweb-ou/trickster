{if $element->canBeDisplayed()}
	{assign 'productsListElement' $element->getProductsListElement()}
	{if $element->title}
		{capture assign="moduleTitle"}
			{$element->title}
		{/capture}
	{/if}
	{capture assign="moduleContent"}
		<script>

			{if $element->canActLikeFilter()}
				window.productsListElementUrl = '{$productsListElement->URL}';
			{else}
				window.productsListElementUrl = '{$productsListElement->URL}id:{$productsListElement->id}/action:search/';
			{/if}
			{if $catalogueElement = $element->getProductCatalogue()}
			window.productSearchCatalogueUrl = '{$catalogueElement->URL}';
			{/if}
			window.categoriesUrls = window.categoriesUrls || {ldelim}{rdelim};
			{if $currentElement->type == 'category'}
				window.categoriesUrls[{$currentElement->id}] = '{$currentElement->URL}';
			{/if}

		</script>

		<div class="productsearch_categories">
			{foreach $element->getFiltersByType('category') as $filter}
					{include $theme->template('productSearch.dropdownfilter.tpl') class="productsearch_field" titleType="option"}
					<script>

						{foreach $filter->getOptionsInfo() as $optionInfo}
							window.categoriesUrls[{$optionInfo.id}] = '{$optionInfo.url}';
						{/foreach}

					</script>
			{/foreach}
		</div>

		{foreach $element->getFiltersByType('brand') as $filter}
			{include $theme->template('productSearch.dropdownfilter.tpl') class="productsearch_field" titleType="option"}
		{/foreach}

		{foreach $element->getFiltersByType('parameter') as $filter}
				{if $element->checkboxesForParameters}
					{include $theme->template('productSearch.checkboxesfilter.tpl')}
				{else}
					{include $theme->template('productSearch.dropdownfilter.tpl') class="productsearch_field" titleType="option"}
				{/if}
		{/foreach}

		{foreach $element->getFiltersByType('discount') as $filter}
				{include $theme->template('productSearch.dropdownfilter.tpl') class="productsearch_field" titleType="option"}
		{/foreach}

		{foreach $element->getFiltersByType('availability') as $filter}
				{include $theme->template('productSearch.dropdownfilter.tpl') class="productsearch_field" titleType="option"}
		{/foreach}

		{foreach $element->getFiltersByType('price') as $filter}
				{if $element->pricePresets}
					{include $theme->template('productSearch.dropdownfilter.tpl') class="productsearch_field" titleType="option"}
				{else}
					{include $theme->template('productSearch.pricefilter.tpl')}
				{/if}
		{/foreach}

		{if $element->canActLikeFilter() || !$element->pageDependent}
			{if $element->sortingEnabled}
				<div class="productsearch_field">
					<select class="productsearch_sortselect dropdown_placeholder">
						{foreach $element->getSortingOptions() as $sortParameter}
							<option value='{$sortParameter.value}'{if $controller->getParameter('sort') == $sortParameter.value} selected="selected"{/if}>
								{$sortParameter.label}
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
