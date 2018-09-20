<?php
/**
 * @package     mod_evangelizo
 * @version     1.1
 * @author      Nicolas Fischmeister - Agence web Cibles
 * @link        http://www.cibles.fr
 * @copyright   Copyright (C) 2018 Agence web Cibles. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
?>

<div class="evangelizo<?php echo $moduleclass_sfx; ?>">
	<?php if($params->get('fete') == 1 && !empty($fete)) : ?>
		<<?php echo $params->get('balise'); ?> class="feast">
			<?php
			$fete = preg_replace('#(.*\(.+) (.+\).*)#', '\1&nbsp;\2', $fete);
			$fete = preg_replace('#(.*\(.+) (.+\).*)#', '\1&nbsp;\2', $fete);
			echo $fete;
			?>
		</<?php echo $params->get('balise'); ?>>
	<?php endif; ?>
	<?php
	if ($params->get('saint') == 1 && sizeof($saints) > 0) :
		foreach ($saints AS $i => $saint) :
			if ( $i < $params->get('saints') && ( ($params->get('niveau') == 1 && $saint->order1 == 1) || $params->get('niveau', 0) == 0 ) ) :
			$nom_saint = $saint->name;
			$nom_saint = preg_replace('#(.*\(.+) (.+\).*)#', '\1&nbsp;\2', $nom_saint);
			$nom_saint = preg_replace('#(.*\(.+) (.+\).*)#', '\1&nbsp;\2', $nom_saint);
			$short_description_saint = $saint->short_description;
			$short_description_saint = preg_replace('#(.*\(.+) (.+\).*)#', '\1&nbsp;\2', $short_description_saint);
			$short_description_saint = preg_replace('#(.*\(.+) (.+\).*)#', '\1&nbsp;\2', $short_description_saint);
			?>
				<<?php echo $params->get('balise'); ?> class="saint">
					<?php
					$modal_params = array();
					$modal_params['title'] = $nom_saint.(!empty(trim($saint->short_description)) ? ' <span style="font-size:0.8em; font-weight:300;">⋅&nbsp;'.$short_description_saint.'</span>' : '');
					$modal_params['backdrop'] = 'true';
					$modal_params['height'] = 'auto';
					$modal_params['footer'] = $saint->bio_source;
					if(isset($saint->image_links->ico) && !empty($saint->image_links->ico))
						$image = '<img src="'.$saint->image_links->ico.'" alt="'.addslashes($saint->name).'" />';
					elseif(isset($saint->image_links->face) && !empty($saint->image_links->face))
						$image = '<img src="'.$saint->image_links->face.'" alt="'.addslashes($saint->name).'" />';
					elseif(isset($saint->image_links->large) && !empty($saint->image_links->large))
						$image = '<img src="'.$saint->image_links->large.'" alt="'.addslashes($saint->name).'" />';
					else
						$image = '';
					$body = '<div>'.$image.$saint->bio.'<div style="clear:both;"></div></div>';
					?>
					<a href="#evangelizo<?php echo strtolower($params->get('langue', 'AM')).$i; ?>" data-toggle="modal" class="hasTip" title="<b><?php echo $nom_saint; ?></b>::<?php echo mb_strtoupper(mb_substr($short_description_saint, 0, 1)).mb_substr($short_description_saint, 1); ?>"><?php echo $nom_saint; ?></a>
				</<?php echo $params->get('balise'); ?>>
				<?php echo JHTML::_('bootstrap.renderModal', 'evangelizo'.strtolower($params->get('langue', 'AM')).$i, $modal_params, $body); ?>
			<?php
			endif;
		endforeach;
	endif;
	?>
</div>
