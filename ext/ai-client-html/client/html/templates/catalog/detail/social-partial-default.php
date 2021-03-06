<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Metaways Infosystems GmbH, 2014
 * @copyright Aimeos (aimeos.org), 2015-2016
 */

$enc = $this->encoder();

/** client/html/catalog/detail/social/list
 * List of social network names that should be displayed in the catalog detail view
 *
 * Users can share product links in several social networks. The order of the
 * social network names in the configuration determines the order of the links
 * on the catalog detail page.
 *
 * You can add more social links only by configuration if you define a
 * corresponding URL for the added social network. For example, if you would
 * like to add Tumblr as social network, you also need to configure a link with
 * the placeholder for the URL:
 *
 *  client/html/catalog/detail/social/list = array( ..., 'tumblr' )
 *  client/html/catalog/detail/social/url/tumblr = http://www.tumblr.com/share/link?url=%1$s&name=%2$s
 *
 * Possible placeholders and replaced values are:
 * * %1$s : Shop URL of the product detail page
 * * %2$s : Name of the product
 * * %3$s : URL of the first product image
 *
 * @param array List of social network names
 * @since 2014.09
 * @category User
 * @category Developer
 * @see client/html/catalog/detail/social/url/facebook
 * @see client/html/catalog/detail/social/url/gplus
 * @see client/html/catalog/detail/social/url/twitter
 * @see client/html/catalog/detail/social/url/pinterest
 */
$list = $this->config( 'client/html/catalog/detail/social/list', array( 'facebook', 'gplus', 'twitter', 'pinterest', 'instagram' ) );

$urls = array(
	/** client/html/catalog/detail/social/url/facebook
	 * URL for sharing product links on Facebook
	 *
	 * Users can share product links on Facebook. This requires a URL defined
	 * by Facebook that accepts the transmitted product page URL. This URL must
	 * contain at least the "%1$s" placeholder for the URL to the product detail
	 * page of the shop.
	 *
	 * Possible placeholders and replaced values are:
	 * * %1$s : Shop URL of the product detail page
	 * * %2$s : Name of the product
	 * * %3$s : URL of the first product image
	 *
	 * @param string URL to share products on Facebook
	 * @since 2014.09
	 * @category User
	 * @category Developer
	 * @see client/html/catalog/detail/social/list
	 */
	'facebook' => 'https://www.facebook.com/sharer.php?u=%1$s&t=%2$s',

	/** client/html/catalog/detail/social/url/gplus
	 * URL for sharing product links on Google Plus
	 *
	 * Users can share product links on Google Plus. This requires a URL defined
	 * by Google Plus that accepts the transmitted product page URL. This URL must
	 * contain at least the "%1$s" placeholder for the URL to the product detail
	 * page of the shop.
	 *
	 * Possible placeholders and replaced values are:
	 * * %1$s : Shop URL of the product detail page
	 * * %2$s : Name of the product
	 * * %3$s : URL of the first product image
	 *
	 * @param string URL to share products on Google Plus
	 * @since 2014.09
	 * @category User
	 * @category Developer
	 * @see client/html/catalog/detail/social/list
	 */
	'gplus' => 'https://plus.google.com/share?url=%1$s',

	/** client/html/catalog/detail/social/url/twitter
	 * URL for sharing product links on Twitter
	 *
	 * Users can share product links on Twitter. This requires a URL defined
	 * by Twitter that accepts the transmitted product page URL. This URL must
	 * contain at least the "%1$s" placeholder for the URL to the product detail
	 * page of the shop.
	 *
	 * Possible placeholders and replaced values are:
	 * * %1$s : Shop URL of the product detail page
	 * * %2$s : Name of the product
	 * * %3$s : URL of the first product image
	 *
	 * @param string URL to share products on Twitter
	 * @since 2014.09
	 * @category User
	 * @category Developer
	 * @see client/html/catalog/detail/social/list
	 */
	'twitter' => 'https://twitter.com/share?url=%1$s&text=%2$s',

	/** client/html/catalog/detail/social/url/pinterest
	 * URL for sharing product links on Pinterest
	 *
	 * Users can share product links on Pinterest. This requires a URL defined
	 * by Pinterest that accepts the transmitted product page URL. This URL must
	 * contain the "%1$s", "%2$s" and "%3$s" placeholders for the URL to the
	 * product detail page, the product name and the product image to be useful.
	 *
	 * Possible placeholders and replaced values are:
	 * * %1$s : Shop URL of the product detail page
	 * * %2$s : Name of the product
	 * * %3$s : URL of the first product image
	 *
	 * @param string URL to share products on Pinterest
	 * @since 2014.09
	 * @category User
	 * @category Developer
	 * @see client/html/catalog/detail/social/list
	 */
	'pinterest' => 'https://pinterest.com/pin/create/button/?url=%1$s&description=%2$s&media=%3$s',
	'instagram' => 'https://www.instagram.com/explore/tags/%2$s/?hl=nl',

);

$detailTarget = $this->config( 'client/html/catalog/detail/url/target' );
$detailController = $this->config( 'client/html/catalog/detail/url/controller', 'catalog' );
$detailAction = $this->config( 'client/html/catalog/detail/url/action', 'detail' );
$detailConfig = $this->config( 'client/html/catalog/detail/url/config', array() );
$detailConfig['absoluteUri'] = true;

$prodName = $this->product->getName();
$param = array( 'd_prodid' => $this->product->getId(), 'd_name' => $prodName );
$prodUrl = $this->url( $detailTarget, $detailController, $detailAction, $param, array(), $detailConfig );

$images = $this->product->getRefItems( 'media', 'default', 'default' );
$prodImage = ( ( $image = reset( $images ) ) !== false ? $this->content( $image->getUrl() ) : '' );

?>
<div class="catalog-detail-social">
<?php foreach( $list as $entry ) : $default = ( isset( $urls[$entry] ) ? $urls[$entry] : null ); ?>
	<?php if( ( $link = $this->config( 'client/html/catalog/detail/social/url/' . $entry, $default ) ) !== null ) : ?>

		<a class="social-button social-button-<?php echo $enc->attr( $entry ); ?>"
			href="<?php echo $enc->attr( sprintf( $link, $enc->url( $prodUrl ), $enc->url( $prodName ), $enc->url( $prodImage ) ) ); ?>"
			title="<?php echo $enc->attr( $entry ); ?>"
			target="_blank"
		></a>

	<?php endif; ?>
<?php endforeach; ?>
</div>
