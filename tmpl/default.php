<?php
defined('_JEXEC') or die;
require_once JPATH_SITE . '/components/com_k2/helpers/route.php';
$categories = $params->get('categories');
$items_limit = $params->get('items_total');
$items_per_column = $params->get('items_per_column');
$effects = $params->get('effects');
$categories = $params->get('categories');
$show_category = $params->get('show_category');
$extra_field_sequence = $params->get('extra_field_sequence');
$custom_title = $params->get('custom_title');
foreach ($categories as $key => $category)
{
  $ids[] = $category;
}
$db = JFactory::getDBO();
$db->setQuery("
SELECT DISTINCT i.id as item_id,i.catid as item_catid,i.title as item_title,i.alias as item_alias,i.published as item_published,i.trash as item_trash,i.extra_fields as item_extra_fields, c.id as category_id, c.parent as category_parent, c.name as category_name, c.alias as category_alias
FROM #__k2_items as i
INNER JOIN #__k2_categories as c
WHERE (i.catid = c.id AND c.parent IN (" . implode(',', $ids) . ") AND i.published = 1 AND i.trash = 0)
OR (i.catid = c.id AND c.id IN (" . implode(',', $ids) . ") AND i.published = 1 AND i.trash = 0)
OR (i.catid = c.parent AND c.parent IN (" . implode(',', $ids) . ") AND i.published = 1 AND i.trash = 0)
ORDER BY item_extra_fields ASC
");
$items = $db->loadObjectList();
$custom_Itemid = $params->get('custom_itemid');
$doc = JFactory::getDocument();
$modulepath = 'modules/mod_' . $module->name;
$doc->addStyleSheet(JURI::root() . "modules/mod_k2_list_items/assets/css/animate.css");
?>
<div class="slideshow-container k2-evens-list <?php echo $module->name . '-' . $module->id; ?>">
    <div class="header-k2events">
        <?php
        if ($custom_title)
        {
          $right_align = ' pull-right';
          $left_align = ' pull-left';
        }
        ?>
        <?php if ($custom_title) : ?>
        <div class="k2events custom-title<?php echo $left_align; ?>">
            <h3 class="modtitle">
                <span class="modtitle-inner"><?php echo $custom_title; ?></span>
            </h3>
        </div>
        <?php endif; ?>
        <div class="buttons<?php echo $right_align; ?>">
            <a class="prev" onclick="plusSlides_<?php echo $module->id; ?>(-1)">
            </a>
            <a class="next" onclick="plusSlides_<?php echo $module->id; ?>(1)">
            </a>
        </div>
    </div>
    <?php $activecount = '0'; ?>
    <?php foreach ($items as $count => $item) : ?>
    <?php $extra_field = json_decode($item->item_extra_fields); ?>
    <?php if ($extra_field[$extra_field_sequence]->value >= date('Y-m-d')) : ?>
    <?php $activecount++; ?>
<?php if ($activecount <= $items_limit) : ?>
     <?php if (($activecount % $items_per_column) == '1') : ?>
      <div class="mySlides-<?php echo $module->id; ?> mySlides">
  <div class="k2-event-items">
  <?php endif; ?>
  <div class="k2-event-item">
<div class="kevent-title">
     <a href="<?php echo JRoute::_('index.php?option=com_k2&view=item&id=' . $item->item_id . ':' . urlencode($item->item_alias) . '&Itemid=' . $custom_Itemid); ?>"><?php echo $item->item_title; ?></a>
   </div>
   <?php if ($show_category == '1') : ?>
       <div class="kevent-category">
     <?php echo JText::_('MOD_ITEM_CATEGORY'); ?><a href="<?php echo urldecode(JRoute::_(K2HelperRoute::getCategoryRoute($item->item_catid . ':' . urlencode($item->category_alias)))); ?>"><?php echo $item->category_name; ?></a>
</div>
<?php endif; ?>
    <div class="kevent-date">
    <?php echo JText::_('MOD_ITEM_DATE') . JHTML::_('date', $extra_field[$extra_field_sequence]->value, JText::_('MOD_K2_DATE_EVENT'), 'UTC'); ?>
</div>
  </div>
     <?php if ((($activecount % $items_per_column) == '0') or ($activecount == $items_limit)) : ?>
  </div>
  </div>
  <?php endif; ?>
    <?php endif; ?>
    <?php endif; ?>
    <?php endforeach; ?>
</div>
<script>
var slideIndex_<?php echo $module->id; ?> = 1;
showSlides_<?php echo $module->id; ?>(slideIndex_<?php echo $module->id; ?>);
function plusSlides_<?php echo $module->id; ?>(n) {
  showSlides_<?php echo $module->id; ?>(slideIndex_<?php echo $module->id; ?> += n);
}
function currentSlide(n) {
  showSlides_<?php echo $module->id; ?>(slideIndex_<?php echo $module->id; ?> = n);
}
function showSlides_<?php echo $module->id; ?>(n) {
  var i;
  var slides = document.getElementsByClassName("mySlides-<?php echo $module->id; ?>");
  var dots = document.getElementsByClassName("dot");
  if (n > slides.length) {slideIndex_<?php echo $module->id; ?> = 1}
  if (n < 1) {slideIndex_<?php echo $module->id; ?> = slides.length}
  for (i = 0; i < slides.length; i++) {
      slides[i].style.display = "none";
      slides[i].className = "mySlides-<?php echo $module->id; ?> mySlides <?php echo $effects; ?> animated";
  }
  for (i = 0; i < dots.length; i++) {
      dots[i].className = dots[i].className.replace(" active", "");
  }
  slides[slideIndex_<?php echo $module->id; ?> - 1].style.display = "block";
   slides[slideIndex_<?php echo $module->id; ?> - 1].className = "mySlides-<?php echo $module->id; ?> mySlides <?php echo $effects; ?> animated";
  dots[slideIndex_<?php echo $module->id; ?> - 1].className += " active";
}
</script>