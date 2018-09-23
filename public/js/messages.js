/*!
 *  Lang.js for Laravel localization in JavaScript.
 *
 *  @version 1.1.10
 *  @license MIT https://github.com/rmariuzzo/Lang.js/blob/master/LICENSE
 *  @site    https://github.com/rmariuzzo/Lang.js
 *  @author  Rubens Mariuzzo <rubens@mariuzzo.com>
 */
(function(root,factory){"use strict";if(typeof define==="function"&&define.amd){define([],factory)}else if(typeof exports==="object"){module.exports=factory()}else{root.Lang=factory()}})(this,function(){"use strict";function inferLocale(){if(typeof document!=="undefined"&&document.documentElement){return document.documentElement.lang}}function convertNumber(str){if(str==="-Inf"){return-Infinity}else if(str==="+Inf"||str==="Inf"||str==="*"){return Infinity}return parseInt(str,10)}var intervalRegexp=/^({\s*(\-?\d+(\.\d+)?[\s*,\s*\-?\d+(\.\d+)?]*)\s*})|([\[\]])\s*(-Inf|\*|\-?\d+(\.\d+)?)\s*,\s*(\+?Inf|\*|\-?\d+(\.\d+)?)\s*([\[\]])$/;var anyIntervalRegexp=/({\s*(\-?\d+(\.\d+)?[\s*,\s*\-?\d+(\.\d+)?]*)\s*})|([\[\]])\s*(-Inf|\*|\-?\d+(\.\d+)?)\s*,\s*(\+?Inf|\*|\-?\d+(\.\d+)?)\s*([\[\]])/;var defaults={locale:"en"};var Lang=function(options){options=options||{};this.locale=options.locale||inferLocale()||defaults.locale;this.fallback=options.fallback;this.messages=options.messages};Lang.prototype.setMessages=function(messages){this.messages=messages};Lang.prototype.getLocale=function(){return this.locale||this.fallback};Lang.prototype.setLocale=function(locale){this.locale=locale};Lang.prototype.getFallback=function(){return this.fallback};Lang.prototype.setFallback=function(fallback){this.fallback=fallback};Lang.prototype.has=function(key,locale){if(typeof key!=="string"||!this.messages){return false}return this._getMessage(key,locale)!==null};Lang.prototype.get=function(key,replacements,locale){if(!this.has(key,locale)){return key}var message=this._getMessage(key,locale);if(message===null){return key}if(replacements){message=this._applyReplacements(message,replacements)}return message};Lang.prototype.trans=function(key,replacements){return this.get(key,replacements)};Lang.prototype.choice=function(key,number,replacements,locale){replacements=typeof replacements!=="undefined"?replacements:{};replacements.count=number;var message=this.get(key,replacements,locale);if(message===null||message===undefined){return message}var messageParts=message.split("|");var explicitRules=[];for(var i=0;i<messageParts.length;i++){messageParts[i]=messageParts[i].trim();if(anyIntervalRegexp.test(messageParts[i])){var messageSpaceSplit=messageParts[i].split(/\s/);explicitRules.push(messageSpaceSplit.shift());messageParts[i]=messageSpaceSplit.join(" ")}}if(messageParts.length===1){return message}for(var j=0;j<explicitRules.length;j++){if(this._testInterval(number,explicitRules[j])){return messageParts[j]}}var pluralForm=this._getPluralForm(number);return messageParts[pluralForm]};Lang.prototype.transChoice=function(key,count,replacements){return this.choice(key,count,replacements)};Lang.prototype._parseKey=function(key,locale){if(typeof key!=="string"||typeof locale!=="string"){return null}var segments=key.split(".");var source=segments[0].replace(/\//g,".");return{source:locale+"."+source,sourceFallback:this.getFallback()+"."+source,entries:segments.slice(1)}};Lang.prototype._getMessage=function(key,locale){locale=locale||this.getLocale();key=this._parseKey(key,locale);if(this.messages[key.source]===undefined&&this.messages[key.sourceFallback]===undefined){return null}var message=this.messages[key.source];var entries=key.entries.slice();var subKey="";while(entries.length&&message!==undefined){var subKey=!subKey?entries.shift():subKey.concat(".",entries.shift());if(message[subKey]!==undefined){message=message[subKey];subKey=""}}if(typeof message!=="string"&&this.messages[key.sourceFallback]){message=this.messages[key.sourceFallback];entries=key.entries.slice();subKey="";while(entries.length&&message!==undefined){var subKey=!subKey?entries.shift():subKey.concat(".",entries.shift());if(message[subKey]){message=message[subKey];subKey=""}}}if(typeof message!=="string"){return null}return message};Lang.prototype._findMessageInTree=function(pathSegments,tree){while(pathSegments.length&&tree!==undefined){var dottedKey=pathSegments.join(".");if(tree[dottedKey]){tree=tree[dottedKey];break}tree=tree[pathSegments.shift()]}return tree};Lang.prototype._applyReplacements=function(message,replacements){for(var replace in replacements){message=message.replace(new RegExp(":"+replace,"gi"),function(match){var value=replacements[replace];var allCaps=match===match.toUpperCase();if(allCaps){return value.toUpperCase()}var firstCap=match===match.replace(/\w/i,function(letter){return letter.toUpperCase()});if(firstCap){return value.charAt(0).toUpperCase()+value.slice(1)}return value})}return message};Lang.prototype._testInterval=function(count,interval){if(typeof interval!=="string"){throw"Invalid interval: should be a string."}interval=interval.trim();var matches=interval.match(intervalRegexp);if(!matches){throw"Invalid interval: "+interval}if(matches[2]){var items=matches[2].split(",");for(var i=0;i<items.length;i++){if(parseInt(items[i],10)===count){return true}}}else{matches=matches.filter(function(match){return!!match});var leftDelimiter=matches[1];var leftNumber=convertNumber(matches[2]);if(leftNumber===Infinity){leftNumber=-Infinity}var rightNumber=convertNumber(matches[3]);var rightDelimiter=matches[4];return(leftDelimiter==="["?count>=leftNumber:count>leftNumber)&&(rightDelimiter==="]"?count<=rightNumber:count<rightNumber)}return false};Lang.prototype._getPluralForm=function(count){switch(this.locale){case"az":case"bo":case"dz":case"id":case"ja":case"jv":case"ka":case"km":case"kn":case"ko":case"ms":case"th":case"tr":case"vi":case"zh":return 0;case"af":case"bn":case"bg":case"ca":case"da":case"de":case"el":case"en":case"eo":case"es":case"et":case"eu":case"fa":case"fi":case"fo":case"fur":case"fy":case"gl":case"gu":case"ha":case"he":case"hu":case"is":case"it":case"ku":case"lb":case"ml":case"mn":case"mr":case"nah":case"nb":case"ne":case"nl":case"nn":case"no":case"om":case"or":case"pa":case"pap":case"ps":case"pt":case"so":case"sq":case"sv":case"sw":case"ta":case"te":case"tk":case"ur":case"zu":return count==1?0:1;case"am":case"bh":case"fil":case"fr":case"gun":case"hi":case"hy":case"ln":case"mg":case"nso":case"xbr":case"ti":case"wa":return count===0||count===1?0:1;case"be":case"bs":case"hr":case"ru":case"sr":case"uk":return count%10==1&&count%100!=11?0:count%10>=2&&count%10<=4&&(count%100<10||count%100>=20)?1:2;case"cs":case"sk":return count==1?0:count>=2&&count<=4?1:2;case"ga":return count==1?0:count==2?1:2;case"lt":return count%10==1&&count%100!=11?0:count%10>=2&&(count%100<10||count%100>=20)?1:2;case"sl":return count%100==1?0:count%100==2?1:count%100==3||count%100==4?2:3;case"mk":return count%10==1?0:1;case"mt":return count==1?0:count===0||count%100>1&&count%100<11?1:count%100>10&&count%100<20?2:3;case"lv":return count===0?0:count%10==1&&count%100!=11?1:2;case"pl":return count==1?0:count%10>=2&&count%10<=4&&(count%100<12||count%100>14)?1:2;case"cy":return count==1?0:count==2?1:count==8||count==11?2:3;case"ro":return count==1?0:count===0||count%100>0&&count%100<20?1:2;case"ar":return count===0?0:count==1?1:count==2?2:count%100>=3&&count%100<=10?3:count%100>=11&&count%100<=99?4:5;default:return 0}};return Lang});

(function () {
    Lang = new Lang();
    Lang.setMessages({"en.admin":{"admin":"Admin","create_user":"Create Users","dashboard":"Dashboard","manage":"Manage admin","manage_category":"Manage Categories","manage_image":"Manage Images","manage_order":"Manage Orders","manage_post":"Manage Posts","manage_product":"Manage Products","manage_user":"Manage Users","profile":"Profile","sign_out":"Sign out"},"en.api":{"data_not_found":"data not found","error":{"unauthorized":"Unauthorized"},"error_404":"Page not found","error_405":"Method Not Allowed","faily":"Faily","register":{"error":{"register":"The given data was invalid."}},"successfully":"Successfully","update":{"error":{"update":"The given data was invalid."}}},"en.auth":{"failed":"These credentials do not match our records.","throttle":"Too many login attempts. Please try again in :seconds seconds."},"en.cart":{"are_you_sure":"Are you sure remove this product?","avail":"Avail.","checkout":"Proceed to checkout","close":").","continue":"Continue shopping","login":"Login","name":"Name","not_exceed":"Do not exceed product quantity in stock (","number_product":"(Number of products currently in stock: ","please_login":"Please login to checkout","price":"Price","product":"Product","qty":"Qty","quantity_greater":"Quantity must be greater than zero.","quantity_stock":"Your cart has exceeded the quantity in stock","shopping_cart":"Shopping Cart","sub_total":"Sub Total","title":"Your Cart","total":"Total"},"en.category":{"admin":{"create":{"category":"Category","create":"Create","form_title":"Create Form","manage_category":"Manage Categories","name":"Name Category","parent":"Name Parent","submit":"Submit","title":"Create Category"},"edit":{"category":"Category","edit":"Edit","form_title":"Edit From","name":"Name Category","parent":"Name Parent","submit":"Submit","title":"Edit Category"},"index":{"action":"Action","categories":"Categories","children":"Children","id":"ID","list_category":"List Category","name":"Name","new_category":"New Category","parent":"Parent","search":"Search","show_product":"Show list products","title":"Show List Categories"},"message":{"fail_create":"Faily Created Category!","fail_delete":"Faily Delete Category!","fail_edit":"Faily Edit Category!","success_create":"Successfully Created Category!","success_delete":"Successfully Delete Category!","success_edit":"Successfully Edit Category!"},"show":{"info":"Category Info"}}},"en.errors":{"create_fail":"Created Fail","delete_fail":"Deleted Fail","message":"Can not find user with corresponding id.","title":"Not find user","update_fail":"Update Fail"},"en.forgot-password":{"account_recovery":"Account Recovery","confirm_password":"Confirm Password","forgot_password":"Forgot Password","forgot_password?":"Forgot Password?","message":"Forgot your account's password? Enter your email address and we'll send you a recovery link.","new_password":"New Password","please_check_mail":"Please check mail!","please_wait":"Please wait a few seconds!","recover_account":"Recover Account","send":"Send Recovery Mail","your_email":"Your Email"},"en.home":{"email":"Email","in_month":"In Month","in_week":"In Week","latest_order":"Latest Orders","order_id":"Order ID","status":"Status","time":"Times","total_ordered":"Total Ordered","total_product":"Total Products","total_product_ordered":"Total Product Ordered","total_revenue":"Total Revenue","user":{"footer":{"all_right":". All Rights Reserved.","copyright":"Copyright \u00a9 2018","food_drink":"Food & Drink"},"header":{"call_us":"Call Us: +123.456.789","cart":"Cart","checkout":"Checkout","edit_profile":"Edit profile","english":"English","history_order":"History orders","items":"Item(s)","language":"Language","log_in":"Log In","log_out":"Log Out","my_account":"My Account","recently_added":"Recently added item(s)","register":"Register","search":"Search","subtotal":"Subtotal: ","vietnam":"Vietnamese","view_cart":"View Cart","welcome":"Welcome to Food & drink!"},"main":{"add_to_cart":"Add to Cart","drink":"Drink","food":"Food","money":"$","new_product":"Top New Product","rate_product":"Top Rate Product","view_more_drink":"Get more products by categoty Drink","view_more_food":"Get more products by categoty Food"},"nav":{"all_product":"All Products","shop_food_drink":"Shop Foods and Drinks"},"title":"Food & Drink","title_edit_user":"Edit User","title_forgot":"Forgot Password"},"view_all_order":"View All Orders"},"en.login":{"user":{"email":"Email","login":"Login","login_fb":"Login With Facebook","password":"Password","register":"Create your very own account!!!","remember_me":"Remember me","unauthorised":"Unauthorised","welcome":"Welcome back! Sign in to your account"}},"en.message":{"order":{"fail_change_status":"Faily Changed Status!"},"post":{"fail_delete":"Faily Deleted Post!","success_delete":"Successfully Deleted Post!"},"product":{"fail_create":"Faily Created Product!","fail_delete":"Faily Deleted Product!","fail_update":"Faily Updated Product!","success_create":"Successfully Created Product!","success_delete":"Successfully Deleted Product!","success_update":"Successfully Updated Product!"},"user":{"create":"Successfully Created User","update":"Successfully Updated User"}},"en.order":{"admin":{"index":{"accepted":"Accepted","action":"Action","address":"Address","confirm_status":"Are you sure change status order?","date":"Date","email_user":"Email User","id":"ID","list_order":"List Orders","money":"$","name_user":"Name User","not_successfully":"Change status not successfully","pending":"Pending","rejected":"Rejected","search":"Search","show_order":"Show list orders","status":"Status","submit":"Submit","title":"Show list orders","total":"Total","write_reason":"Please write your reason!"},"show":{"address":"Address","avatar":"Avatar","back":"Back","content":"Content","date":"Date","email":"Email","form_title":"Show detail","id":"ID","image":"Image","list_note":"List notes","list_product":"List Products","name":"Name","name_product":"Name Product","order":"Order","password":"Password","phone":"Phone","preview":"Preview","quantity":"Quantity","title":"Show detail order","total":"Total","user_info":"User Infomation"}},"user":{"cancel":{"are_your_sure":"Are you cancel this order?","not_successfully":"Cancel order not successfully!!!","send":"Send","successfully":"Canceled order successfully!!!","write_reason":"Please write your reason(*)"},"create":{"checkout":"Checkout","complete":"Complete","home_address":"Use home address","new_address":"Use new place of delivery","old_address":"Use old place of delivery","old_place_delivery":"Old place of delivery","place_delivery":"Place of delivery:","title":"Create Order","your_address":"Your Address: ","your_checkout":"Your checkout","your_email":"Your Email: ","your_name":"Your Name: ","your_phone":"Your Phone: "},"edit":{"cancel_edit":"Cancel Edit Order","title":"Edit Order"},"index":{"address":"Address","cancel":"Cancel","date":"Ordered date","detail":"Detail","edit":"Edit","function":"Function","no":"No.","note":"Note","show_order":"Show list orders","status":"Status","title":"List Order","total_price":"Total Price"},"show":{"description":"Description","order_detail":"Order Detail","title":"Show Order Detail"}}},"en.pagination":{"next":"Next &raquo;","previous":"&laquo; Previous"},"en.passwords":{"password":"Passwords must be at least six characters and match the confirmation.","reset":"Your password has been reset!","sent":"We have e-mailed your password reset link!","token":"This password reset token is invalid.","user":"We can't find a user with that e-mail address."},"en.post":{"index":{"action":"Action","comment":"Comment","id":"ID","list_post":"List Post","list_review":"List Review","post_id":"Post ID","posts":"Posts","product_id":"Product ID","product_name":"Product Name","rate":"Rate","review":"Review","review_id":"Review ID","reviews":"Reviews","show_list_post":"Show List Post","status":"Status","title":"Show Review","type":"Type","user_name":"User Name"}},"en.product":{"admin":{"create":{"category":"Category","create":"Create","description":"Description","enter_description":"Enter description","enter_name":"Enter name","enter_preview":"Enter preview","enter_price":"Enter price","enter_quantity":"Enter quantity","form_title":"Create Form","image":"Image","manage_product":"Manage Products","name":"Name","preview":"Preview","price":"Price","product":"Product","quantity":"Quantity","submit":"Submit","title":"Create Product"},"edit":{"canot_delete_image":"Cannot delete the last image!!!","category":"Category","delete_confirm":"Do you want to delete this Image?","description":"Description","edit":"Edit","edit_product":"Edit Product","form_title":"Edit Form","image":"Image","manage_product":"Manage Products","name":"Name","preview":"Preview","price":"Price","product":"Product","quantity":"Quantity","submit":"Submit","title":"Edit Product"},"index":{"action":"Action","category":"Category","id":"ID","image":"Image","list_product":"List Products","name":"Name","new_product":"New product","price":"Price","product":" Products","quantity":"Quantity","rate_avg":"Rate Avg","search":"Search","show_product":"Show list products","title":"Show List Product"},"show":{"back":"Back","edit_product":"Edit Product","form_title":"Show detail","product":"Product","title":"Show detail product"}},"image_product_default":"default-product.jpg","user":{"add_to_cart":"Add to Cart","detail":{"comment":{"comment":"Comment","comment_active":"Your comment sended and please admin active","comments":"Comments","write_your_comment":"Write Your Own Comment"},"description":"Description","error_message":{"401":"You need login to continue","405":"You need to purchase to review"},"review":{"review":"Review","review_active":"Your review sended and please admin active","reviews":"Reviews","star":"star","stars":"stars","write_your_review":"Write Your Own Review"}},"filter":{"list_product":"List Product","title":"Show All Product"},"money":"$","next":"Next","prev":"Prev","product":{"action":"Action","availability":"Availability","compare":"Compare","compare_product":"Compare Product","description":"Description","list_product":"List Product","name":"Product name","price":"Price","product_detail":"Product Detail","product_image":"Product Image","quantity":"Quantity","quick_view":"QUICK OVERVIEW","rate":"Rate","rating":"Rating"},"recommend":{"related_products":"Related Products"}}},"en.profile":{"user":{"update":{"address":"Address","address_shipping":"Address shipping (set default address)","avatar":"Avatar","edit":"Edit","email":"Email","name":"Name","password":"Password","phone":"Phone","please_fill":"Please fill in this form to update user.","submit":"Submit","update_user":"Update User"}}},"en.sidebar":{"cart_sub":"Cart Subtotal:","categories":"Categories","checkout":"Checkout","in_your_cart":"in your cart.","items":"items","max_price":"Max price","min_price":"Min price","money":"$","my_cart":"My Cart","name_product":"Name product","price":"Price","rate":"Rate","refresh_filter":"Refresh filter","search":"Search","shop_by":"Shop By","there_are":"There are"},"en.user":{"admin":{"create":{"address":"Address","avatar":"Avatar","create":"Create","create_user":"Create User","email":"Email","enter_address":"Enter Address","enter_avatar":"Enter Avatar","enter_email":"Enter Email","enter_name":"Enter Name","enter_password":"Enter Password","enter_phone":"Enter Phone","form":"Form","form_title":"Create Form","name":"Name","password":"Password","phone":"Phone","submit":"Submit","title":"Create User","user":"User"},"edit":{"address":"Address","avatar":"Avatar","edit_user":"Edit User","email":"Email","form_title":"Edit Form","name":"Name","password":"Password","phone":"Phone","submit":"Submit","title":"Edit User","user":"User"},"index":{"action":"Action","address":"Address","avatar":"Avatar","create_success":"Create Success","email":"Email","id":"ID","list_user":"List Users","name":"Name","new_user":"New user","phone":"Phone","search":"Search","show_user":"Show list Users","title":"Show List User","user":" Users"},"message":{"cancel":"can not delete admin","success":"Successfully deleted the user!","success_create":"Successfully created the user!","success_update":"Successfully updated the user!"},"register":{"label_register":"Create your very own account","register":"Register","ship":"Can Be Overlooked"},"show":{"address_shipping":"Address shippings","edit_user":"Edit User","form_title":"Show User","title":"Show User Info"}},"user":{"profile":{"email":"Email:","home_address":"Home address:","name":"Name:","new_address":"New shipping address: ","phone":"Phone:","profile":"Your profile","shipping_address":"Shipping address:","title":"Your Profile"},"shipping":{"add":"Add","create_not_success":"Create new shipping address not successfully","create_success":"Create new shipping address successfully","edit_not_success":"Edit shipping address not successfully","edit_success":"Edit shipping address successfully","write_address":"Write new your shipping address(*)"}}},"en.validation":{"accepted":"The :attribute must be accepted.","active_url":"The :attribute is not a valid URL.","after":"The :attribute must be a date after :date.","after_or_equal":"The :attribute must be a date after or equal to :date.","alpha":"The :attribute may only contain letters.","alpha_dash":"The :attribute may only contain letters, numbers, and dashes.","alpha_num":"The :attribute may only contain letters and numbers.","array":"The :attribute must be an array.","attributes":[],"before":"The :attribute must be a date before :date.","before_or_equal":"The :attribute must be a date before or equal to :date.","between":{"array":"The :attribute must have between :min and :max items.","file":"The :attribute must be between :min and :max kilobytes.","numeric":"The :attribute must be between :min and :max.","string":"The :attribute must be between :min and :max characters."},"boolean":"The :attribute field must be true or false.","confirmed":"The :attribute confirmation does not match.","custom":{"attribute-name":{"rule-name":"custom-message"}},"date":"The :attribute is not a valid date.","date_format":"The :attribute does not match the format :format.","different":"The :attribute and :other must be different.","digits":"The :attribute must be :digits digits.","digits_between":"The :attribute must be between :min and :max digits.","dimensions":"The :attribute has invalid image dimensions.","distinct":"The :attribute field has a duplicate value.","email":"The :attribute must be a valid email address.","exists":"The selected :attribute is invalid.","file":"The :attribute must be a file.","filled":"The :attribute field must have a value.","gt":{"array":"The :attribute must have more than :value items.","file":"The :attribute must be greater than :value kilobytes.","numeric":"The :attribute must be greater than :value.","string":"The :attribute must be greater than :value characters."},"gte":{"array":"The :attribute must have :value items or more.","file":"The :attribute must be greater than or equal :value kilobytes.","numeric":"The :attribute must be greater than or equal :value.","string":"The :attribute must be greater than or equal :value characters."},"image":"The :attribute must be an image.","in":"The selected :attribute is invalid.","in_array":"The :attribute field does not exist in :other.","integer":"The :attribute must be an integer.","ip":"The :attribute must be a valid IP address.","ipv4":"The :attribute must be a valid IPv4 address.","ipv6":"The :attribute must be a valid IPv6 address.","json":"The :attribute must be a valid JSON string.","lt":{"array":"The :attribute must have less than :value items.","file":"The :attribute must be less than :value kilobytes.","numeric":"The :attribute must be less than :value.","string":"The :attribute must be less than :value characters."},"lte":{"array":"The :attribute must not have more than :value items.","file":"The :attribute must be less than or equal :value kilobytes.","numeric":"The :attribute must be less than or equal :value.","string":"The :attribute must be less than or equal :value characters."},"max":{"array":"The :attribute may not have more than :max items.","file":"The :attribute may not be greater than :max kilobytes.","numeric":"The :attribute may not be greater than :max.","string":"The :attribute may not be greater than :max characters."},"mimes":"The :attribute must be a file of type: :values.","mimetypes":"The :attribute must be a file of type: :values.","min":{"array":"The :attribute must have at least :min items.","file":"The :attribute must be at least :min kilobytes.","numeric":"The :attribute must be at least :min.","string":"The :attribute must be at least :min characters."},"not_in":"The selected :attribute is invalid.","not_regex":"The :attribute format is invalid.","numeric":"The :attribute must be a number.","present":"The :attribute field must be present.","regex":"The :attribute format is invalid.","required":"The :attribute field is required.","required_if":"The :attribute field is required when :other is :value.","required_unless":"The :attribute field is required unless :other is in :values.","required_with":"The :attribute field is required when :values is present.","required_with_all":"The :attribute field is required when :values is present.","required_without":"The :attribute field is required when :values is not present.","required_without_all":"The :attribute field is required when none of :values are present.","same":"The :attribute and :other must match.","size":{"array":"The :attribute must contain :size items.","file":"The :attribute must be :size kilobytes.","numeric":"The :attribute must be :size.","string":"The :attribute must be :size characters."},"string":"The :attribute must be a string.","timezone":"The :attribute must be a valid zone.","unique":"The :attribute has already been taken.","uploaded":"The :attribute failed to upload.","url":"The :attribute format is invalid."},"vi.api":{"data_not_found":"D\u1eef li\u1ec7u kh\u00f4ng t\u1ed3n t\u1ea1i","error":{"unauthorized":"Kh\u00f4ng \u0111\u01b0\u1ee3c ph\u00e9p"},"error_404":"Trang kh\u00f4ng t\u1ed3n t\u1ea1i","error_405":"Ph\u01b0\u01a1ng th\u1ee9c kh\u00f4ng \u0111\u01b0\u1ee3c ph\u00e9p","faily":"L\u1ed7i","register":{"error":{"register":"D\u1eef li\u1ec7u \u0111\u00e3 cho kh\u00f4ng h\u1ee3p l\u1ec7."}},"successfully":"Th\u00e0nh c\u00f4ng","update":{"error":{"update":"D\u1eef li\u1ec7u \u0111\u00e3 cho kh\u00f4ng h\u1ee3p l\u1ec7."}}},"vi.cart":{"are_you_sure":"B\u1ea1n c\u00f3 ch\u1eafc ch\u1eafn lo\u1ea1i b\u1ecf s\u1ea3n ph\u1ea9m n\u00e0y kh\u1ecfi gi\u1ecf h\u00e0ng?","avail":"T\u00ecnh tr\u1ea1ng","checkout":"Ti\u1ebfn h\u00e0nh thanh to\u00e1n","close":").","continue":"Ti\u1ebfp t\u1ee5c mua h\u00e0ng","name":"T\u00ean","not_exceed":"Kh\u00f4ng v\u01b0\u1ee3t qu\u00e1 s\u1ed1 l\u01b0\u1ee3ng trong kho (","number_product":"(S\u1ed1 l\u01b0\u1ee3ng s\u1ea3n ph\u1ea9m c\u00f2n l\u1ea1i trong kho: ","please_login":"Vui l\u00f2ng \u0111\u0103ng nh\u1eadp \u0111\u1ec3 thanh to\u00e1n","price":"Gi\u00e1","product":"S\u1ea3n ph\u1ea9m","qty":"S\u1ed1 l\u01b0\u1ee3ng","quantity_greater":"S\u1ed1 l\u01b0\u1ee3ng ph\u1ea3i l\u1edbn h\u01a1n 0.","shopping_cart":"Gi\u1ecf h\u00e0ng","sub_total":"Th\u00e0nh ti\u1ec1n:","title":"Gi\u1ecf h\u00e0ng c\u1ee7a b\u1ea1n","total":"T\u1ed5ng c\u1ed9ng"},"vi.errors":{"create_fail":"T\u1ea1o m\u1edbi b\u1ecb l\u1ed7i","delete_fail":"X\u00f3a b\u1ecb l\u1ed7i","message":"Kh\u00f4ng t\u00ecm th\u1ea5y ng\u01b0\u1eddi d\u00f9ng v\u1edbi id t\u01b0\u01a1ng \u1ee9ng.","title":"Kh\u00f4ng t\u00ecm th\u1ea5y ng\u01b0\u1eddi d\u00f9ng","update_fail":"C\u1eadp nh\u1eadt b\u1ecb l\u1ed7i"},"vi.forgot-password":{"account_recovery":"T\u00e0i kho\u1ea3n ph\u1ee5c h\u1ed3i","confirm_password":"X\u00e1c nh\u1eadn m\u1eadt kh\u1ea9u","forgot_password":"Qu\u00ean m\u1eadt kh\u1ea9u","forgot_password?":"Qu\u00ean m\u1eadt kh\u1ea9u?","message":"Qu\u00ean m\u1eadt kh\u1ea9u t\u00e0i kho\u1ea3n c\u1ee7a b\u1ea1n? Nh\u1eadp \u0111\u1ecba ch\u1ec9 email v\u00e0 ch\u00fang t\u00f4i s\u1ebd g\u1eedi cho b\u1ea1n m\u1ed9t \u0111\u01b0\u1eddng link ph\u1ee5c h\u1ed3i","new_password":"M\u1eadt kh\u1ea9u m\u1edbi","please_check_mail":"Vui l\u00f2ng ki\u1ec3m tra mail!","please_wait":"Vui l\u00f2ng \u0111\u1ee3i v\u00e0i gi\u00e2y!","recover_account":"Ph\u1ee5c h\u1ed3i t\u00e0i kho\u1ea3n","send":"G\u1eedi mail x\u00e1c nh\u1eadn","your_email":"Email c\u1ee7a b\u1ea1n"},"vi.home":{"user":{"footer":{"all_right":". \u0110\u00e3 \u0111\u0103ng k\u00fd b\u1ea3n quy\u1ec1n.","copyright":"Copyright \u00a9 2018","food_drink":"\u0110\u1ed3 \u0103n v\u00e0 N\u01b0\u1edbc u\u1ed1ng"},"header":{"call_us":"Li\u00ean h\u1ec7 v\u1edbi ch\u00fang t\u00f4i: +123.456.789","cart":"Gi\u1ecf h\u00e0ng","checkout":"Thanh to\u00e1n","edit_profile":"Ch\u1ec9nh S\u1eeda h\u1ed3 s\u01a1","english":"Ti\u1ebfng Anh","history_order":"L\u1ecbch s\u1eed  \u0111\u01a1n h\u00e0ng","items":"S\u1ea3n ph\u1ea9m(n)","language":"Ng\u00f4n ng\u1eef","log_in":"\u0110\u0103ng nh\u1eadp","log_out":"\u0110\u0103ng xu\u1ea5t","my_account":"T\u00e0i kho\u1ea3n c\u1ee7a t\u00f4i","recently_added":"Danh s\u00e1ch s\u1ea3n ph\u1ea9m \u0111\u01b0\u1ee3c th\u00eam v\u00e0o(n)","register":"\u0110\u0103ng k\u00fd","search":"T\u00ecm ki\u1ebfm","subtotal":"T\u1ed5ng ti\u1ec1n: ","vietnam":"Ti\u1ebfng Vi\u1ec7t","view_cart":"Xem gi\u1ecf h\u00e0ng","welcome":"Ch\u00e0o m\u1eebng t\u1edbi Food & drink!"},"main":{"add_to_cart":"Th\u00eam v\u00e0o gi\u1ecf h\u00e0ng","drink":"N\u01b0\u1edbc u\u1ed1ng","food":"\u0110\u1ed3 \u0103n","money":"$","new_product":"S\u1ea3n ph\u1ea9m m\u1edbi nh\u1ea5t","rate_product":"S\u1ea3n ph\u1ea9m \u0111\u01b0\u1ee3c \u0111\u00e1nh gi\u00e1 cao nh\u1ea5t","view_more_drink":"Xem nhi\u1ec1u s\u1ea3n ph\u1ea9m h\u01a1n theo danh m\u1ee5c n\u01b0\u1edbc u\u1ed1ng","view_more_food":"Xem nhi\u1ec1u s\u1ea3n ph\u1ea9m h\u01a1n theo danh m\u1ee5c \u0111\u1ed3 \u0103n"},"nav":{"all_product":"T\u1ea5t c\u1ea3 s\u1ea3n ph\u1ea9m","shop_food_drink":"Shop \u0110\u1ed3 \u0103n v\u00e0 N\u01b0\u1edbc u\u1ed1ng"},"title":"\u0110\u1ed3 \u0103n v\u00e0 N\u01b0\u1edbc u\u1ed1ng","title_edit_user":"Ch\u1ec9nh s\u1eeda Ng\u01b0\u1eddi d\u00f9ng","title_forgot":"Qu\u00ean m\u1eadt kh\u1ea9u"}},"vi.login":{"user":{"email":"Email","login":"\u0110\u0103ng nh\u1eadp","password":"Nh\u1eadt kh\u1ea9u","register":"T\u1ea1o t\u00e0i kho\u1ea3n cho b\u1ea1n!!!","remember_me":"Nh\u1edb m\u1eadt kh\u1ea9u","unauthorised":"Kh\u00f4ng \u0111\u01b0\u1ee3c ph\u00e9p","welcome":"Ch\u00e0o m\u1eebng tr\u1edd l\u1ea1i! \u0110\u0103ng nh\u1eadp v\u00e0o t\u00e0i kho\u1ea3n c\u1ee7a b\u1ea1n"}},"vi.message":{"order":{"fail_change_status":"Thay \u0110\u1ed5i Tr\u1ea1ng Th\u00e1i B\u1ecb L\u1ed7i!"},"post":{"fail_delete":"X\u00f3a B\u00e0i \u0110\u0103ng B\u1ecb L\u1ed7i!","success_delete":"\u0110\u00e3 X\u00f3a B\u00e0i \u0110\u0103ng Th\u00e0nh C\u00f4ng!"},"product":{"fail_create":"T\u1ea1o S\u1ea3n Ph\u1ea9m B\u1ecb L\u1ed7i!","fail_delete":"X\u00f3a S\u1ea3n Ph\u1ea9m B\u1ecb L\u1ed7i!","fail_update":"C\u1eadp Nh\u1eadt S\u1ea3n Ph\u1ea9m B\u1ecb L\u1ed7i!","success_create":"\u0110\u00e3 T\u1ea1o S\u1ea3n Ph\u1ea9m Th\u00e0nh C\u00f4ng!","success_delete":"\u0110\u00e3 X\u00f3a S\u1ea3n Ph\u1ea9m Th\u00e0nh C\u00f4ng!","success_update":"\u0110\u00e3 C\u1eadp Nh\u1eadt S\u1ea3n Ph\u1ea9m Th\u00e0nh C\u00f4ng!"},"user":{"create":"\u0110\u00e3 T\u1ea1o T\u00e0i Kho\u1ea3n Th\u00e0nh C\u00f4ng","update":"\u0110\u00e3 C\u1eadp Nh\u1eadt T\u00e0i Kho\u1ea3n Th\u00e0nh C\u00f4ng"}},"vi.order":{"user":{"cancel":{"are_your_sure":"B\u1ea1n c\u00f3 ch\u1eafc ch\u1eafn h\u1ee7y \u0111\u01a1n h\u00e0ng n\u00e0y?","not_successfully":"H\u1ee7y \u0111\u01a1n h\u00e0ng kh\u00f4ng th\u00e0nh c\u00f4ng!!!","send":"G\u1eedi","successfully":"\u0110\u00e3 h\u1ee7y \u0111\u01a1n h\u00e0ng th\u00e0nh c\u00f4ng!!!","write_reason":"Vui l\u00f2ng vi\u1ebft l\u00fd do(*)"},"create":{"checkout":"Thanh to\u00e1n","complete":"Ho\u00e0n th\u00e0nh","home_address":"Ch\u1ecdn \u0111\u1ecba ch\u1ec9 nh\u00e0","new_address":"Ch\u1ecdn \u0111\u1ecba \u0111i\u1ec3m giao h\u00e0ng m\u1edbi","old_address":"Ch\u1ecdn \u0111\u1ecba \u0111i\u1ec3m giao h\u00e0ng c\u0169","old_place_delivery":"\u0110\u1ecba \u0111i\u1ec3m giao h\u00e0ng c\u0169","place_delivery":"\u0110\u1ecba ch\u1ec9 giao h\u00e0ng:","title":"T\u1ea1o \u0110\u01a1n H\u00e0ng","your_address":"\u0110\u1ecba ch\u1ec9 c\u1ee7a b\u1ea1n: ","your_checkout":"Ki\u1ec3m tra \u0111\u01a1n h\u00e0ng c\u1ee7a b\u1ea1n","your_email":"Email c\u1ee7a b\u1ea1n: ","your_name":"T\u00ean c\u1ee7a b\u1ea1n: ","your_phone":"S\u1ed1 \u0111i\u1ec7n tho\u1ea1i c\u1ee7a b\u1ea1n: "},"edit":{"cancel_edit":"H\u1ee7y ch\u1ec9nh s\u1eeda","title":"Ch\u1ec9nh S\u1eeda \u0110\u01a1n H\u00e0ng"},"index":{"address":"\u0110\u1ecba ch\u1ec9","cancel":"H\u1ee7y b\u1ecf","date":"Ng\u00e0y \u0111\u1eb7t h\u00e0ng","detail":"Chi ti\u1ebft","edit":"S\u1eeda","function":"Ch\u1ee9c n\u0103ng","no":"STT","note":"Ch\u00fa th\u00edch","show_order":"Hi\u1ec7n danh s\u00e1ch d\u1eb7t h\u00e0ng","status":"Tr\u1ea1ng th\u00e1i","title":"Danh S\u00e1ch \u0110\u1eb7t H\u00e0ng","total_price":"T\u1ed5ng gi\u00e1 ti\u1ec1n"},"show":{"description":"M\u00f4 t\u1ea3","order_detail":"Chi ti\u1ebft \u0111\u01a1n h\u00e0ng","title":"Hi\u1ec3n Th\u1ecb Chi Ti\u1ebft \u0110\u01a1n H\u00e0ng"}}},"vi.product":{"image_product_default":"default-product.jpg","user":{"add_to_cart":"Th\u00eam v\u00e0o gi\u1ecf h\u00e0ng","detail":{"comment":{"comment":"B\u00ecnh lu\u1eadn","comment_active":"B\u00ecnh lu\u1eadn c\u1ee7a b\u1ea1n \u0111\u00e3 \u0111\u01b0\u1ee3c g\u1eedi v\u00e0 vui l\u00f2ng ch\u1edd qu\u1ea3n tr\u1ecb vi\u00ean ch\u1ea5p nh\u1eadn","comments":"B\u00ecnh lu\u1eadn","write_your_comment":"Vi\u1ebft b\u00ecnh lu\u1eadn c\u1ee7a b\u1ea1n"},"description":"M\u00f4 t\u1ea3","error_message":{"401":"B\u1ea1n c\u1ea7n \u0111\u0103ng nh\u1eadp \u0111\u1ec3 ti\u1ebfp t\u1ee5c","405":"B\u1ea1n c\u1ea7n mua s\u1ea3n ph\u1ea9m \u0111\u1ec3 reivew"},"review":{"review":"Nh\u1eadn x\u00e9t","review_active":"Nh\u1eadn x\u00e9t c\u1ee7a b\u1ea1n \u0111\u00e3 \u0111\u01b0\u1ee3c g\u1eedi v\u00e0 vui l\u00f2ng ch\u1edd qu\u1ea3n tr\u1ecb vi\u00ean ch\u1ea5p nh\u1eadn","reviews":"Nh\u1eadn x\u00e9t","star":"sao","stars":"sao","write_your_review":"Vi\u1ebft nh\u1eadn x\u00e9t c\u1ee7a b\u1ea1n"}},"filter":{"list_product":"Danh s\u00e1ch s\u1ea3n ph\u1ea9m","title":"Hi\u1ec3n Th\u1ecb T\u1ea5t C\u1ea3 S\u1ea3n Ph\u1ea9m"},"money":"$","next":"Trang sau","prev":"Trang tr\u01b0\u1edbc","product":{"action":"Ho\u1ea1t \u0110\u1ed9ng","availability":"S\u1ed1 L\u01b0\u1ee3ng","compare":"So S\u00e1nh","compare_product":"So S\u00e1nh S\u1ea3n Ph\u1ea9m","description":"Chi Ti\u1ebft","list_product":"Danh s\u00e1ch s\u1ea3n ph\u1ea9m","name":"T\u00ean","price":"Gi\u00e1 ","product_detail":"Chi ti\u1ebft s\u1ea3n ph\u1ea9m","product_image":"H\u00ecnh","quantity":"S\u1ed1 l\u01b0\u1ee3ng","quick_view":"T\u1ed5ng quan nhanh","rate":"\u0110\u00e1nh gi\u00e1","rating":"\u0110\u00e1nh Gi\u00e1"},"recommend":{"related_products":"S\u1ea3n ph\u1ea9m li\u00ean quan"}}},"vi.profile":{"user":{"update":{"address":"\u0110\u1ecba ch\u1ec9","address_shipping":"\u0110\u1ecba ch\u1ec9 giao h\u00e0ng (\u0111\u1ecba ch\u1ec9 giao h\u00e0ng m\u1eb7c \u0111\u1ecbnh)","avatar":"H\u00ecnh \u0111\u1ea1i di\u1ec7n","edit":"S\u1ee7a","email":"Email","name":"T\u00ean","password":"M\u1eadt kh\u1ea9u","phone":"\u0110i\u1ec7n tho\u1ea1i","please_fill":"Vui l\u00f2ng \u0111i\u1ec1n v\u00e0o bi\u1ec5u m\u1eabu d\u01b0\u1edbi \u0111\u1ec3 c\u1eadp nh\u1eadt ng\u01b0\u1eddi d\u00f9ng.","submit":"G\u1eedi","update_user":"C\u1eadp nh\u1eadt ng\u01b0\u1eddi d\u00f9ng"}}},"vi.sidebar":{"categories":"Danh m\u1ee5c","max_price":"Gi\u00e1 t\u1ed1i \u0111a","min_price":"Gi\u00e1 t\u1ed1i thi\u1ec3u","money":"$","name_product":"T\u00ean s\u1ea3n ph\u1ea9m","price":"Gi\u00e1 ti\u1ec1n","rate":"\u0110\u00e1nh gi\u00e1","refresh_filter":"L\u00e0m m\u1edbi b\u1ed9 l\u1ecdc","search":"T\u00ecm ki\u1ebfm","shop_by":"Mua s\u1eafm "},"vi.user":{"admin":{"create":{"address":"\u0110\u1ecba ch\u1ec9","avatar":"H\u00ccnh \u0111\u1ea1i di\u1ec7n","create":"T\u1ea1o m\u1edbi","create_user":"T\u1ea1o ngu\u1eddi d\u00f9ng","email":"Email","enter_address":"Nh\u1eadp \u0111\u1ecba ch\u1ec9","enter_avatar":"Ch\u1ecdn \u1ea3nh","enter_email":"Nh\u1eadp Email","enter_name":"Nh\u1eadp t\u00ean","enter_password":"Nh\u1eadp m\u1eadt kh\u1ea9u","enter_phone":"Nh\u1eadp s\u1ed1 \u0111i\u1ec7n tho\u1ea1i","form":"BI\u1ec3u m\u1eabu","form_title":"T\u1ea1o bi\u1ec3u m\u1eabu","name":"T\u00ean","password":"M\u1eadt Kh\u1ea9u","phone":"S\u1ed1 \u0111i\u1ec7n tho\u1ea1i","submit":"G\u1eedi","title":"T\u1ea1o Ng\u01b0\u1eddi D\u00f9ng","user":"Ng\u01b0\u1eddi d\u00f9ng"},"register":{"label_register":"T\u1ea1o t\u00e0i kho\u1ea3n cho b\u1ea1n","register":"\u0110\u0103ng k\u00fd","ship":"C\u00f3 th\u1ec3 b\u1ecf qua"}},"user":{"profile":{"email":"Email:","home_address":"\u0110\u1ecba ch\u1ec9 nh\u00e0:","name":"T\u00ean:","new_address":"\u0110\u1ecba ch\u1ec9 giao h\u00e0ng m\u1edbi: ","phone":"S\u1ed1 \u0111i\u1ec7n tho\u1ea1i:","profile":"Th\u00f4ng tin c\u00e1 nh\u00e2n","shipping_address":"\u0110\u1ecba ch\u1ec9 giao h\u00e0ng:","title":"Th\u00f4ng Tin C\u00e1 Nh\u00e2n"},"shipping":{"add":"Th\u00eam","write_address":"Vi\u1ebft \u0111\u1ecba ch\u1ec9 giao h\u00e0ng m\u1edbi c\u1ee7a b\u1ea1n(*)"}}}});
})();
