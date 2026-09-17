import { symphonyHtml } from "./symphony-html";

const figmaAssets = {
  hero: "/images/symphony-iv-figma-live/hero.png",
  // The former mobile export pointed at an unrelated pendant mood shot.
  // Use the Symphony IV hero and crop it responsively, as in the Figma frame.
  mobileHero: "/images/symphony-iv-figma-live/hero.png",
  collection: "/images/symphony-iv-figma-live/collection.png",
  thumbnails: [
    "/images/symphony-iv-figma-live/thumb-1.png",
    "/images/symphony-iv-figma-live/thumb-2.png",
    "/images/symphony-iv-figma-live/thumb-3.png",
    "/images/symphony-iv-figma-live/thumb-4.png",
    "/images/symphony-iv-figma-live/thumb-5.png",
    "/images/symphony-iv-figma-live/thumb-6.png",
  ],
  // The Figma thumbnail exports are only 23–84 px wide. They are intentionally
  // kept in the strip, but must never be promoted into the large image viewer.
  // Use the original product renders for the stage/lightbox instead.
  gallery: [
    "/images/decorative/symphonyiv-on.png",
    "/images/symphony-iv-figma-live/hero.png",
    "/images/decorative/symphonyiv-black-white.png",
    "/images/symphony-iv-figma-live/hero.png",
    "/images/decorative/symphonyiv-gold-white.png",
    "/images/symphony-iv-figma-live/hero.png",
  ],
  family: [
    "/images/symphony-iv-figma-live/family-v.png",
    "/images/symphony-iv-figma-live/family-vi.png",
    "/images/symphony-iv-figma-live/family-vii.png",
  ],
};

const figmaCss = `<style id="symphony-iv-figma-refresh">
.product-page .product-reveal{opacity:1!important;transform:none!important}
.product-page .product-nav{height:95px!important;background:#000!important}
.product-page .product-nav .sitehead{position:relative!important;inset:auto!important;display:flex!important;visibility:visible!important;opacity:1!important;transform:none!important;height:95px!important;background:#000!important}
.product-page .product-shell{max-width:1250px;padding-top:0}
.product-page .product-breadcrumb{height:auto!important;margin:0 0 10px!important;padding:37px 0 15px!important;font-size:11px;color:#777}
.product-page .product-top{grid-template-columns:minmax(0,668px) minmax(360px,500px);gap:71px;align-items:start;padding-bottom:90px!important}
.product-page .product-gallery{display:flex;flex-direction:column}
.product-page .product-stage{height:486px;border-radius:3px;background:#eee;overflow:hidden}
.product-page .product-stage img{width:100%;height:100%;object-fit:cover}
.product-page .product-stage>span{display:none!important}
.product-page .thumb-carousel{margin-top:0;height:84px}
.product-page .thumb-carousel{position:relative;overflow:hidden}
.product-page .product-thumbs{gap:10px}
.product-page .product-thumbs button{flex:0 0 84px;width:84px;height:84px;border:0}
.product-page .product-thumbs button:nth-child(n+7){display:none!important}
.product-page .product-thumbs img{width:100%;height:100%;object-fit:cover}
.product-page .thumb-edge-preview{position:absolute;top:0;width:47px;height:84px;z-index:2;overflow:hidden;opacity:.34;pointer-events:none;border-radius:3px;background:#eee}
.product-page .thumb-edge-preview img{width:84px;height:84px;max-width:none;object-fit:cover}
.product-page .thumb-edge-left{left:0}.product-page .thumb-edge-right{right:0}
.product-page .thumb-edge-right img{transform:translateX(-37px)}
.product-page .gallery-arrow{background:rgba(255,255,255,.18)!important;z-index:3}
.product-page .product-info{padding-top:7px}
.product-page .product-tag{text-transform:uppercase;letter-spacing:.02em;font-size:13px;color:#8b8b8b;margin:0}
.product-page .product-info h1{font-size:36px;font-weight:500;line-height:1.18;margin:22px 0 13px}
.product-page .product-desc{max-width:500px;font-size:13px;line-height:1.8;color:#333;margin:0 0 29px;padding-bottom:29px;border-bottom:1px solid #d8d8d8}
.product-page .symphony-colour-option{position:relative;margin:0!important;padding:0!important;border:0!important}
.product-page .product-option>strong{display:block;text-transform:uppercase;font-size:13px;line-height:17px;font-weight:400;color:#8a8a8a;margin:0 0 12px}
.product-page .combination-swatches{gap:4px;align-items:flex-start}
.product-page .combination-swatches button{min-width:46px}
.product-page .combination-swatches button>i{width:36px;height:36px}
.product-page .combination-swatches button>i{background:linear-gradient(135deg,var(--finish-a) 0 50%,var(--finish-b) 50% 100%)!important;border:3px solid #fff!important;border-radius:50%!important;box-shadow:0 0 0 1px #d4d4d4!important;box-sizing:border-box!important;display:block!important;overflow:hidden}
.product-page .combination-swatches button>i:after{display:none!important}
.product-page .combination-swatches button.active>i{box-shadow:0 0 0 2px #111!important}
.product-page .combination-swatches button>span{font-size:9px;line-height:1.15;margin-top:5px}
.product-page .more-colours{display:flex!important;align-items:center!important;justify-content:center!important;min-width:72px!important;width:auto!important;color:#999}
.product-page .more-colours>i{display:none!important}
.product-page .more-colours>span:before{content:"+"}
.product-page .more-colours>span{font-size:12px!important;white-space:nowrap;margin-top:5px!important}
.product-page .inline-colour-panel-wrap:not(.open) .inline-colour-panel{padding:0!important;min-height:0!important}
.product-page .product-option:not(.symphony-colour-option){margin-top:5px!important;padding:0!important;border:0!important}
.product-page .size-options{gap:0}
.product-page .size-options button{height:23px;padding:0 10px;border:0;background:#050505;color:#fff;font-size:9px;border-radius:2px}
.product-page .size-options button:nth-child(n+2){display:none!important}
.product-page .product-enquire{width:100%;height:38px;margin-top:40px;border-radius:2px;letter-spacing:.04em}
.product-page .product-help{margin-top:17px!important;font-size:11px}
.product-page .product-sku{display:none!important}
.product-page .inline-colour-panel{min-height:0;padding:0!important;overflow:hidden}
.product-page .inline-colour-panel .colour-library{grid-template-columns:repeat(12,19px)!important;gap:8px!important;margin:0!important;padding:10px 11px 5px!important}
.product-page .inline-colour-panel .colour-library>div,.product-page .inline-colour-panel .colour-library i{width:19px!important;height:19px!important;padding:0!important}
.product-page .colour-library>div{cursor:pointer;border:1px solid transparent;border-radius:50%;padding:3px}
.product-page .colour-library>div.selected{border-color:#111}
.product-page .inline-colour-panel>p{margin:0!important;padding:3px 11px 8px!important;font-size:8px;line-height:11px;letter-spacing:.02em}
.product-page .collection-band{height:637px;margin-top:0;position:relative;overflow:hidden}
.product-page .collection-band>img{width:100%;height:100%;object-fit:cover}
.product-page .collection-band>div{position:absolute!important;z-index:2;left:max(48px,calc((100vw - 1250px)/2));right:auto;top:277px;bottom:auto;width:466px!important;height:auto!important;max-width:calc(100% - 96px);padding:0!important;justify-content:flex-start!important}
.product-page .collection-band>div>p{display:none!important}
.product-page .collection-band>div h2{display:flex;flex-direction:column;margin:0;color:#fff;font:400 36.764px/56.561px Poppins,sans-serif;letter-spacing:-1.2868px}
.product-page .collection-band>div h2 em{display:block;margin-top:-10px;color:#f6c177;font:400 italic 37.168px/46.461px "Libre Baskerville",serif;letter-spacing:0}
.product-page .collection-band>div>span{display:block;width:466px;max-width:100%;margin-top:25px;color:#fff;font:300 18px/1.2 Inter,sans-serif}
.product-page .collection-band>div>a{display:grid;place-items:center;box-sizing:border-box;width:233px;height:38px;margin-top:34px;padding:0;font:400 14px/1 Poppins,sans-serif}
.product-page .product-specs{max-width:1210px;margin-top:6px;padding:62px 0 70px}
.product-page .product-specs>h2,.product-page .related-section>h2{font-size:39px;font-weight:500}
.product-page .spec-grid{border-top:1px solid #111}
.product-page .spec-body p span{white-space:pre-line}
.product-page .spec-body .figma-spec-note{display:block;margin-top:5px;font-size:12px;font-style:italic;font-weight:200;line-height:1.35;white-space:normal}
.product-page .spec-code{display:inline-block!important;margin-right:10px;color:#c0c0c0;font:700 10px/1 Inter,sans-serif;font-style:normal!important;vertical-align:middle}
.product-page:has(.symphony-stage) .spec-grid>.spec-accordion:nth-child(2),.product-page:has(.symphony-stage) .spec-grid>.spec-accordion:nth-child(3){display:none}
.product-page:has(.symphony-stage) .spec-grid>.spec-accordion:first-child .spec-body p{grid-template-columns:310px 1fr}
.product-page:has(.symphony-stage) .spec-grid>.spec-accordion:first-child .spec-body p:nth-child(n+7):nth-child(-n+11){grid-template-columns:275px 1fr;margin:0;padding-left:55px;padding-right:28px;border-left:.5px solid #aaa;border-right:.5px solid #aaa}
.product-page:has(.symphony-stage) .spec-grid>.spec-accordion:first-child .spec-body p:nth-child(7){margin-top:22px;border-top:.5px solid #aaa}
.product-page:has(.symphony-stage) .spec-grid>.spec-accordion:first-child .spec-body p:nth-child(11){margin-bottom:10px;border-bottom:.5px solid #aaa}
.product-page .spec-grid>details{margin-top:24px}
.figma-specs{padding-top:26px}
.figma-spec-head,.figma-download-head{display:flex;justify-content:space-between;align-items:center;font-weight:600;font-size:15px;padding:0 0 20px}
.figma-spec-row{display:grid;grid-template-columns:310px 1fr;gap:0;min-height:48px;padding:13px 0;border-bottom:1px solid #e2e2e2;font-size:13px;line-height:1.42}
.figma-spec-row>span:first-child{color:#777;font-weight:300}
.figma-spec-row small{display:block;font-size:10px;font-style:italic;line-height:1.45;margin-top:4px}
.figma-dimensions{border:.5px solid #aaa;margin:22px 0 10px;padding:0 28px}
.figma-dimensions .figma-spec-row{grid-template-columns:280px 1fr}
.figma-downloads{margin-top:64px;border-bottom:1px solid #e2e2e2;padding-bottom:26px}
.figma-download-buttons{display:flex;gap:14px}
.figma-download-buttons button{border:.5px solid #bbb;background:#fff;color:#777;padding:12px 20px;font:300 12px Inter,sans-serif}
.product-page .download-row{gap:14px}.product-page .download-row button{cursor:pointer}
.product-page .related-section{max-width:1250px;padding:20px 0 88px}
.product-page .related-section>h2{margin:0 0 44px 13px}
.product-page .related-carousel{padding:0 28px;box-sizing:border-box}
.product-page .related-track{display:flex!important;gap:22px;overflow:hidden}
.product-page .related-track>a{position:relative;flex:0 0 384px;min-width:384px;background:#fafafa;text-decoration:none;color:#000}
.product-page .related-track>a:nth-child(n+4){display:none!important}
.product-page .related-track>a>img{display:block;width:100%;height:362px;object-fit:none;object-position:center top;background:#ececec;border-radius:0!important}
.product-page .related-track>a h3{font:600 18px/1.15 Inter,sans-serif;margin:28px 22px 6px}
.product-page .related-track>a>span{display:block;font:300 14px/1.3 Inter,sans-serif;color:#888;margin:0 22px 14px}
.product-page .related-track>a:after{content:"";display:block;width:50px;height:13px;margin:0 22px 25px;background:radial-gradient(circle at 6px 6px,#f4f1e7 0 5px,#d8d4c9 5.5px 6px,transparent 6.5px),radial-gradient(circle at 25px 6px,#151515 0 6px,transparent 6.5px),radial-gradient(circle at 44px 6px,#9a472b 0 6px,transparent 6.5px)}
.figma-enquiry{position:fixed;inset:0;z-index:10000;background:rgba(0,0,0,.45);display:grid;place-items:center;padding:28px}
.figma-enquiry[hidden]{display:none}
.figma-enquiry-card{position:relative;width:min(760px,100%);max-height:94vh;overflow:auto;background:#fff;padding:52px 70px 34px}
.figma-enquiry-close{position:absolute;right:24px;top:18px;border:0;background:none;font-size:28px;font-weight:200}
.figma-enquiry h2{font:600 25px/1.2 Inter,sans-serif;margin:0 0 8px}
.figma-enquiry-lead{font:300 14px/1.5 Inter,sans-serif;color:#777;margin:0 0 44px}
.figma-enquiry-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px}
.figma-enquiry input,.figma-enquiry select,.figma-enquiry textarea{width:100%;border:1px solid #ccc;background:#fff;padding:16px;font:300 14px Inter,sans-serif;color:#555;box-sizing:border-box}
.figma-enquiry .full{grid-column:1/-1}.figma-enquiry textarea{min-height:112px;resize:vertical}
.figma-captcha{width:180px;border:1px solid #ddd;padding:14px;margin:20px 0 30px;font-size:11px}.figma-captcha i{display:inline-block;width:18px;height:18px;border:1px solid #111;vertical-align:middle;margin-right:10px}
.figma-enquiry-submit{display:block;width:225px;margin:0 auto;border:0;background:#f4bc70;padding:14px;font:500 13px Inter,sans-serif;letter-spacing:.06em}
.figma-enquiry-consent{text-align:center;color:#888;font-size:11px;margin-top:24px}.figma-enquiry-consent em{color:#e4a85d;font-style:normal}
.figma-lightbox{position:fixed;inset:0;z-index:10001;display:grid;place-items:center;background:rgba(0,0,0,.92);padding:42px}
.figma-lightbox[hidden]{display:none}.figma-lightbox img{max-width:min(1080px,88vw);max-height:86vh;object-fit:contain}
.figma-lightbox button{position:absolute;border:0;background:transparent;color:#fff;font:300 42px/1 Inter,sans-serif;cursor:pointer}.figma-lightbox-close{right:28px;top:22px}.figma-lightbox-prev{left:28px;top:50%}.figma-lightbox-next{right:28px;top:50%}
.mobile-category{display:none}
.finish-label-mobile{display:none}
@media (max-width:900px){
 .product-page .product-nav,.product-page .product-nav .sitehead{height:72px!important}
 .product-page .product-shell{padding:0 16px}
 .product-page .product-breadcrumb{margin-bottom:14px!important;padding:30px 0 14px!important;font-size:9px}
 .product-page .product-top{display:flex;flex-direction:column;gap:18px;padding-bottom:0!important}
 .product-page .product-gallery{display:flex;flex-direction:column}
 .product-page .product-stage{order:0;width:100%;height:auto;aspect-ratio:1/1.25}
 .product-page .thumb-carousel{order:1;margin-top:12px}
 .product-page .product-info{padding-top:4px}
 .product-page .product-info h1{font-size:24px}
 .product-page .collection-band{height:520px;margin-top:48px}
 .product-page .collection-band>div{left:24px;right:auto;top:210px;bottom:auto;max-width:360px}
 .product-page .product-specs{padding:42px 18px 48px}
 .product-page .product-specs>h2,.product-page .related-section>h2{font-size:25px}
 .figma-spec-row,.figma-dimensions .figma-spec-row{grid-template-columns:42% 58%;font-size:11px;padding:11px 0;min-height:40px}
 .figma-dimensions{padding:0 14px}
 .product-page:has(.symphony-stage) .spec-grid>.spec-accordion:first-child .spec-body p{grid-template-columns:42% 58%}
 .product-page:has(.symphony-stage) .spec-grid>.spec-accordion:first-child .spec-body p:nth-child(n+7):nth-child(-n+11){grid-template-columns:42% 58%;padding-left:14px;padding-right:14px}
 .figma-download-buttons{gap:7px;flex-wrap:wrap}.figma-download-buttons button{padding:9px 10px;font-size:9px}
 .product-page .related-section{padding:12px 18px 70px}
 .product-page .related-section>h2{margin-left:0}
 .product-page .related-carousel{padding:0}
 .product-page .related-track>a{min-width:calc((100vw - 54px)/2)}
 .product-page .related-track>a>img{height:220px;object-fit:contain}
 .product-page .related-track>a h3{font-size:12px;margin:11px 12px 3px}.product-page .related-track>a>span{font-size:10px;margin:0 12px 9px}.family-finishes{margin:0 12px 14px}.family-finishes i{width:10px;height:10px}
 .figma-enquiry{padding:0}.figma-enquiry-card{width:100%;height:100%;max-height:none;padding:56px 22px 30px}.figma-enquiry-grid{grid-template-columns:1fr}.figma-enquiry .full{grid-column:1}.figma-enquiry-lead{margin-bottom:28px}
}
@media (max-width:600px){
 html,body,.product-page{max-width:100%;overflow-x:hidden}
 .product-page .product-nav,.product-page .product-nav .sitehead{height:78px!important}
 .product-page .product-shell{width:auto!important;max-width:none!important;margin:0!important;padding:0 29px 0 23px!important}
 .product-page .product-breadcrumb{padding:30px 0 14px!important;margin:0!important;font-size:11px;line-height:13px}
 .product-page .product-top{gap:43px!important}
 .product-page .product-stage{width:100%!important;height:370px!important;aspect-ratio:auto!important}
 .product-page .thumb-carousel{height:56px!important;margin-top:19px!important}
 .product-page .product-thumbs{gap:7px!important}
 .product-page .product-thumbs button{flex:0 0 56px!important;width:56px!important;height:56px!important}
 .product-page .gallery-arrow{width:45px!important;height:56px!important}
 .product-page .thumb-edge-preview{width:45px;height:56px}
 .product-page .thumb-edge-preview img{width:56px;height:56px}
 .product-page .thumb-edge-right img{transform:translateX(-11px)}
 .product-page .product-info{padding:0 6px!important}
 .product-page .product-tag{font-size:11px;line-height:13px}
 .product-page .product-info h1{font-size:31px!important;line-height:1.15;margin:14px 0 4px!important}
 .mobile-category{display:block;margin:0 0 20px;font:400 14px/1.3 Inter,sans-serif;color:#777}
 .product-page .product-desc{font-size:11px;line-height:1.45;margin:0 0 14px;padding-bottom:20px}
 .product-page .product-option>strong{font-size:11px;line-height:14px;margin-bottom:8px}
 .finish-label-desktop{display:none}.finish-label-mobile{display:inline}
 .product-page .combination-swatches{grid-template-columns:repeat(5,45px)!important;gap:0 5px!important;margin-left:-13px}
 .product-page .combination-swatches button,.product-page .combination-swatches button>span{width:45px!important;min-width:45px!important}
 .product-page .combination-swatches button>i{width:27px!important;height:27px!important;min-width:27px!important;flex:0 0 27px!important;aspect-ratio:1/1!important}
 .product-page .combination-swatches button>span{font-size:7px;margin-top:4px}
 .product-page .more-colours{min-width:54px!important}
 .product-page .more-colours>span{font-size:9px!important}
 .product-page .product-option:not(.symphony-colour-option){margin-top:8px!important}
 .product-page .size-options{display:flex!important;justify-content:flex-start!important}
 .product-page .size-options button{flex:0 0 auto!important;width:auto!important;min-width:57px!important;height:31px;padding:0 13px;font-size:9px}
 .product-page .product-enquire{height:44px;margin-top:38px;background:#f6c177;color:#000}
 .product-page .product-help{margin-top:17px!important;font-size:11px}
 .product-page .collection-band{height:419px!important;margin-top:52px!important}
 .product-page .collection-band>div{position:absolute!important;left:22px!important;right:auto!important;top:195px!important;bottom:auto!important;width:290px!important;height:auto!important;max-width:calc(100% - 44px)!important;padding:0!important;justify-content:flex-start!important}
 .product-page .collection-band>div h2{font:600 20.881px/25px Poppins,sans-serif!important;letter-spacing:.8352px!important}
 .product-page .collection-band>div h2 em{margin-top:-1px!important;font:400 italic 19.844px/24.804px "Libre Baskerville",serif!important;letter-spacing:0!important}
 .product-page .collection-band>div>span{width:260px!important;max-width:100%!important;margin-top:25px!important;font:300 10px/1.2 Inter,sans-serif!important}
 .product-page .collection-band>div>a{width:158px!important;height:34px!important;margin-top:21px!important;padding:0!important;font:400 9px/1 Poppins,sans-serif!important}
 .product-page .product-specs{width:auto!important;max-width:none!important;margin:0!important;padding:54px 22px 44px!important}
 .product-page .product-specs>h2{font-size:25.6px!important;margin-bottom:28px!important}
 .product-page .spec-grid>details{margin-top:24px!important}
 .product-page .spec-accordion>button{font-size:15px!important;padding:18px 0!important}
 .product-page .spec-body p{grid-template-columns:127px 1fr!important;gap:12px!important;font-size:11px!important;padding:12px 0!important}
 .product-page .spec-body .figma-spec-note{font-size:9px;line-height:1.35;margin-top:6px}
 .product-page:has(.symphony-stage) .spec-grid>.spec-accordion:first-child .spec-body p:nth-child(n+7):nth-child(-n+11){grid-template-columns:127px 1fr!important;padding-left:20px!important;padding-right:8px!important}
 .figma-downloads{margin-top:24px;padding-bottom:24px}.figma-download-head{padding-bottom:16px;font-size:15px}
 .figma-download-buttons{display:grid;grid-template-columns:134px 134px;gap:8px!important}
 .figma-download-buttons button{width:134px;height:31px;padding:0 7px!important;font-size:9px!important}
 .product-page .spec-grid>details summary{padding:18px 0!important;font-size:15px!important}
 .product-page .download-row{display:grid!important;grid-template-columns:134px 134px!important;gap:8px!important;padding-bottom:24px!important}
 .product-page .download-row button{width:134px!important;height:31px!important;padding:0 7px!important;font-size:9px!important}
 .product-page .related-section{padding:12px 22px 70px!important}.product-page .related-section>h2{font-size:25.6px!important;margin:0 0 24px!important}
 .product-page .related-track{gap:10px!important}.product-page .related-track>a{flex-basis:calc((100vw - 54px)/2)!important;min-width:calc((100vw - 54px)/2)!important}
}

/* Intentional motion system */
:root{--ease-out:cubic-bezier(.23,1,.32,1);--ease-in-out:cubic-bezier(.77,0,.175,1);--ease-drawer:cubic-bezier(.32,.72,0,1)}
.product-page .product-nav{position:sticky;top:0;z-index:100}.product-page .product-nav:after{content:"";position:absolute;z-index:9;left:0;right:0;bottom:0;height:1px;background:#ffffff2e;opacity:0;transition:opacity 200ms var(--ease-out)}
.product-page .product-nav.header-scrolled:after{opacity:1}.product-page .product-nav .sitehead .wrap{transition:transform 200ms var(--ease-out)}.product-page .product-nav.header-scrolled .sitehead .wrap{transform:translateY(-1px)}
.product-page .sitehead nav .link{position:relative}.product-page .sitehead nav .link:after{content:"";position:absolute;left:0;right:0;bottom:-5px;height:1px;background:currentColor;transform:scaleX(0);transform-origin:left;transition:transform 150ms var(--ease-out)}
.product-page .nav-cta{transition:transform 140ms var(--ease-out),background-color 150ms ease}.product-page .nav-cta:active,.product-page .product-enquire:active,.product-page .collection-band a:active{transform:scale(.97)}
.product-page .abby-search-toggle{transition:transform 120ms var(--ease-out)}.product-page .abby-search-toggle:active{transform:scale(.92)}
html.motion-ready .product-breadcrumb{opacity:0;transition:opacity 300ms var(--ease-out)}html.motion-ready.motion-loaded .product-breadcrumb{opacity:1}.product-page .product-breadcrumb a{transition:color 150ms ease}
html.motion-ready .product-stage>img{opacity:0;transform:scale(.98);transition:opacity 400ms var(--ease-out),transform 400ms var(--ease-out),filter 200ms var(--ease-out)}html.motion-ready.motion-loaded .product-stage>img{opacity:1;transform:scale(1)}
.product-page .product-stage{position:relative}.product-page .product-stage>img.stage-outgoing{position:absolute;z-index:2;inset:0;pointer-events:none;opacity:1;transform:scale(1);transition:opacity 200ms var(--ease-out),filter 200ms var(--ease-out)!important}.product-page .product-stage>img.stage-outgoing.is-leaving{opacity:0;filter:blur(2px)}
.product-page .product-stage>img.stage-incoming{opacity:0!important}.product-page .product-stage>img.stage-incoming.is-arrived{opacity:1!important}
.product-page .product-thumbs{overflow:visible!important;will-change:transform;transition:transform 250ms var(--ease-out)}
.product-page .product-thumbs button{position:relative;transition:transform 200ms var(--ease-out),opacity 150ms var(--ease-out)!important}.product-page .product-thumbs button:after{content:"";position:absolute;inset:0;border:1px solid #111;opacity:0;pointer-events:none;transition:opacity 150ms var(--ease-out)}.product-page .product-thumbs button.active{transform:scale(1.03)}.product-page .product-thumbs button.active:after{opacity:1}.product-page .gallery-arrow,.product-page .related-arrow{transition:transform 120ms var(--ease-out),opacity 150ms ease}.product-page .gallery-arrow:active,.product-page .related-arrow:active{transform:scale(.9)}
html.motion-ready .motion-info-item{opacity:0;transform:translateY(8px);transition:opacity 300ms var(--ease-out),transform 300ms var(--ease-out);transition-delay:var(--motion-delay,0ms)}html.motion-ready.motion-loaded .motion-info-item{opacity:1;transform:none}
.product-page .combination-swatches button{position:relative;transition:transform 140ms var(--ease-out)}.product-page .combination-swatches button:before{content:"";position:absolute;left:50%;top:-2px;width:40px;height:40px;border:1px solid #111;border-radius:50%;opacity:0;transform:translateX(-50%) scale(.9);transition:opacity 150ms var(--ease-out),transform 150ms var(--ease-out)}.product-page .combination-swatches button:active{transform:scale(.95)}.product-page .combination-swatches button.active{transform:scale(1.03)}.product-page .combination-swatches button.active:before{opacity:1;transform:translateX(-50%) scale(1)}
.product-page .combination-swatches button.active>i{box-shadow:0 0 0 1px #d4d4d4!important}
.product-page .inline-colour-panel-wrap{position:relative;z-index:30;top:auto;left:auto;width:338px;max-width:100%;display:grid!important;grid-template-rows:0fr!important;margin:0!important;opacity:0;visibility:hidden;pointer-events:none;transform:scale(.96);transform-origin:100% 0;transition:grid-template-rows 200ms var(--ease-drawer),opacity 200ms var(--ease-out),transform 200ms var(--ease-out)}.product-page .inline-colour-panel-wrap.open{grid-template-rows:1fr!important;margin-top:10px!important;opacity:1;visibility:visible;pointer-events:auto;transform:scale(1)}
.product-page .inline-colour-panel>p{transition:opacity 150ms var(--ease-out),transform 150ms var(--ease-out)}.product-page .inline-colour-panel>p.label-changing{opacity:0;transform:translateY(4px)}
.product-page .product-enquire{transition:transform 160ms var(--ease-out),background-color 150ms ease,color 150ms ease}.product-page .product-help a{transition:color 150ms ease,text-decoration-color 150ms ease}
html.motion-ready .collection-band>img{transform:scale(1.05);transition:transform 700ms var(--ease-in-out)}html.motion-ready .collection-band.collection-inview>img{transform:scale(1)}
html.motion-ready .collection-band>div>*{opacity:0;transform:translateY(12px);transition:opacity 500ms var(--ease-out),transform 500ms var(--ease-out);transition-delay:var(--collection-delay,0ms)}html.motion-ready .collection-band.collection-inview>div>*{opacity:1;transform:none}
.product-page .spec-body{transition:grid-template-rows 280ms var(--ease-drawer),opacity 220ms var(--ease-drawer)!important}.product-page .spec-accordion>button i{position:relative;width:16px;height:16px;font-size:0!important;transition:transform 200ms var(--ease-out)}.product-page .spec-accordion>button i:before,.product-page .spec-accordion>button i:after{content:"";position:absolute;left:2px;right:2px;top:7px;height:1px;background:#737373}.product-page .spec-accordion>button i:after{transform:rotate(90deg) scaleX(0);transition:transform 200ms var(--ease-out)}.product-page .spec-accordion:not(.open)>button i:after{transform:rotate(90deg) scaleX(1)}
.product-page .download-row button{transition:color 150ms ease,border-color 150ms ease}.product-page .download-row button:after{content:none!important;display:none!important}
.product-page .related-carousel{overflow:hidden!important}.product-page .related-track{display:flex!important;overflow:visible!important;will-change:transform;transition:transform 300ms var(--ease-out)!important}.product-page .related-track>a:nth-child(n){display:block!important;overflow:hidden;transition:transform 300ms var(--ease-out),opacity 400ms var(--ease-out)!important;opacity:0;transform:translateY(16px);transition-delay:var(--family-delay,0ms)!important}.product-page .related-track>a:before{content:"";position:absolute;z-index:4;inset:0;pointer-events:none;box-shadow:inset 0 0 0 1px #00000008,0 12px 28px #00000012;opacity:0;transition:opacity 300ms var(--ease-out)}.product-page .related-section.family-inview .related-track>a{opacity:1;transform:none}.product-page .related-section.family-settled .related-track>a{transition-delay:0ms!important}
.product-page .related-track>a>img{transition:transform 300ms var(--ease-out)!important}.product-page .related-track>a:after{transition:transform 150ms var(--ease-out)}
.product-page .related-arrow{display:grid!important;z-index:8}.product-page .related-prev{left:0!important}.product-page .related-next{right:0!important}
html.motion-ready .figma-footer{opacity:0;transform:translateY(8px);transition:opacity 400ms var(--ease-out),transform 400ms var(--ease-out)}html.motion-ready .figma-footer.footer-inview{opacity:1;transform:none}
.product-page .footer-accordion-panel{opacity:0;display:grid!important;grid-template-rows:0fr;transition:grid-template-rows 250ms var(--ease-drawer),opacity 200ms var(--ease-drawer)}.product-page .footer-accordion-panel>*{overflow:hidden}.product-page .footer-accordion.open .footer-accordion-panel{opacity:1;grid-template-rows:1fr}.product-page .footer-accordion-icon{transition:transform 200ms var(--ease-out)}.product-page .footer-accordion.open .footer-accordion-icon{transform:rotate(180deg)}.product-page .figma-footer a{transition:color 150ms ease}
@media (hover:hover) and (pointer:fine){
 .product-page .sitehead nav .link:hover:after{transform:scaleX(1)}.product-page .nav-cta:hover{background:#f8ca8d}.product-page .product-breadcrumb a:hover{color:#111}
 .product-page .product-thumbs button:hover{transform:scale(1.03)}.product-page .combination-swatches button:hover{transform:scale(1.03)}.product-page .combination-swatches button:hover:before{opacity:.55;transform:translateX(-50%) scale(1)}
 .product-page .product-enquire:hover{background:#f6c177!important;color:#111!important}.product-page .product-help a:hover{color:#4caf50;text-decoration-color:#4caf50}
 .product-page .download-row button:hover{color:#111;border-color:#777}.product-page .download-row button:hover:after{transform:translateX(2px)}
 .product-page .related-section.family-inview .related-track>a:hover{transform:translateY(-4px)}.product-page .related-track>a:hover:before{opacity:1}.product-page .related-track>a:hover>img{transform:scale(1.04)!important}.product-page .related-track>a:hover:after{transform:scale(1.15)}
 .product-page .figma-footer a:hover{color:#fff}
}
@media (max-width:600px){.product-page .related-carousel{padding:0!important}.product-page .related-track>a{flex:0 0 calc((100vw - 54px)/2)!important;min-width:calc((100vw - 54px)/2)!important}.product-page .related-arrow{width:25px!important;height:44px!important;top:136px!important;background:#ffffffc7!important}.product-page .inline-colour-panel-wrap{left:-8px;width:calc(100% + 5px);max-width:calc(100vw - 44px);transform-origin:85% 0}.product-page .inline-colour-panel-wrap.open{margin-top:0!important}.product-page .combination-swatches button:before{top:-2px;width:31px;height:31px}}
@media (min-width:601px){.product-page .footer-accordion-panel{display:block!important;opacity:1!important;grid-template-rows:none!important}.product-page .footer-accordion-panel>*{overflow:visible}}
@media (hover:none),(pointer:coarse){.product-page .product-thumbs button:hover{transform:none!important}.product-page .collection-band:hover>img{transform:scale(1)!important}.product-page .related-track>a:hover>img{transform:none!important}.product-page .related-track>a:hover{transform:none!important}.product-page .related-track>a:hover:before{opacity:0!important}}
@media (prefers-reduced-motion:reduce){
 .product-page .product-nav .sitehead .wrap,.product-page .product-thumbs,.product-page .gallery-arrow,.product-page .related-arrow,.product-page .motion-info-item,.product-page .combination-swatches button,.product-page .inline-colour-panel-wrap,.product-page .product-enquire,.product-page .collection-band>img,.product-page .collection-band>div>*,.product-page .related-track,.product-page .related-track>a,.product-page .related-track>a>img,.product-page .figma-footer{transform:none!important}
 .product-page .product-stage>img{transform:none!important;filter:none!important}.product-page .product-thumbs,.product-page .related-track{transition:none!important}
}
/* Figma fidelity corrections: gallery controls, mobile stage and family cards */
.product-page .product-stage>span{display:block!important;position:absolute;right:13px;bottom:13px;z-index:6;width:26px;height:26px;overflow:hidden;text-indent:-9999px;background:url('/images/product-zoom-icon.png') center/26px 26px no-repeat;pointer-events:none}
.product-page .gallery-arrow{opacity:.33!important}
.product-page:has(.symphony-stage) .spec-grid>.spec-accordion:first-child .spec-body p:nth-child(7){margin-top:38px!important}
.product-page:has(.symphony-stage) .spec-grid>.spec-accordion:first-child .spec-body p:nth-child(11){margin-bottom:34px!important}
@media (max-width:600px){
 .product-page .product-shell{padding-left:24px!important;padding-right:24px!important}
 .product-page .product-gallery{width:100%!important;align-items:stretch!important}
 .product-page .product-stage{display:block!important;width:100%!important;max-width:342px!important;height:auto!important;aspect-ratio:342/370!important;margin-left:auto!important;margin-right:auto!important;align-self:center!important;overflow:hidden!important}
 .product-page .product-stage>img{width:100%!important;height:100%!important;object-fit:cover!important;object-position:center!important}
 .product-page .product-stage>span{right:12px;bottom:12px}
 .product-page:has(.symphony-stage) .spec-grid>.spec-accordion:first-child .spec-body p:nth-child(7){margin-top:36px!important}
 .product-page:has(.symphony-stage) .spec-grid>.spec-accordion:first-child .spec-body p:nth-child(11){margin-bottom:30px!important}
 .product-page .collection-band>div{top:236px!important}
 .product-page .related-section{padding-left:22px!important;padding-right:22px!important}
 .product-page .related-carousel{overflow:hidden!important}
 .product-page .related-track{gap:10px!important}
 .product-page .related-track>a{flex:0 0 calc((100vw - 54px)/2)!important;min-width:calc((100vw - 54px)/2)!important;background:#fafafa!important}
 .product-page .related-track>a>img{display:block!important;width:100%!important;height:auto!important;aspect-ratio:1/1!important;object-fit:contain!important;object-position:center!important;background:#ececec!important}
 .product-page .related-track>a h3{margin:11px 10px 3px!important;font-size:12px!important;line-height:1.2!important}
 .product-page .related-track>a>span{margin:0 10px 8px!important;font-size:10px!important;line-height:1.25!important}
 .product-page .related-track>a:after{margin:0 10px 13px!important}
 .product-page .related-arrow{top:calc(50% - 34px)!important;width:25px!important;height:44px!important;background:rgba(255,255,255,.67)!important}
}
</style>`;

const figmaDomSync = `<script id="symphony-iv-figma-dom-sync">
(()=>{
 document.documentElement.classList.add('motion-ready');
 const hero=${JSON.stringify(figmaAssets.hero)};
 const mobileHero=${JSON.stringify(figmaAssets.mobileHero)};
 const galleryThumbs=${JSON.stringify(figmaAssets.thumbnails)};
 const gallery=${JSON.stringify(figmaAssets.gallery)};
 const family=${JSON.stringify(figmaAssets.family)};
 const specBodyMarkup='<p><b>Category</b><span>Pendant Light</span></p>'+
  '<p><b>Primary Material</b><span>Aluminium</span></p>'+
  '<p><b>Primary Finish</b><span>Black, White, Copper, Black + White, Black + Gold<small class="figma-spec-note">- Format :Top colour + Bottom color<br>- Inner Body Finish will be white by default<br>- Canopy Finish will be white for any fixture having white in the body finish, black canopy for all colours other than white<br>- Wire colour will be white for any fixture having white in the body finish, black canopy for all colours other than white</small></span></p>'+
  '<p><b>Secondary Material</b><span>Aluminium</span></p>'+
  '<p><b>Secondary Finish</b><span>White, Black</span></p>'+
  '<p><b>Wire Colour</b><span>White<small class="figma-spec-note">- Wire colour will be white for any fixture having white in the body finish, black canopy for all colours other than white</small></span></p>'+
  '<p><b>Size</b><span><i class="spec-code">OS</i>Onesize</span></p><p><b>Height</b><span>250 mm</span></p><p><b>Diameter</b><span>Ø 250 mm</span></p><p><b>Canopy</b><span>Ø 95 mm, H 45 mm</span></p><p><b>Wire Length</b><span>1.5 m</span></p>'+
  '<p><b>Light source</b><span>E27 Compatible <small class="figma-spec-note">(Bulb not included)</small></span></p><p><b>Input Voltage</b><span>220V-240V</span></p><p><b>Control</b><span>ON-OFF</span></p>';
 const modal='<div class="figma-enquiry" hidden><div class="figma-enquiry-card" role="dialog" aria-modal="true" aria-labelledby="figma-enquiry-title"><button class="figma-enquiry-close" aria-label="Close enquiry form">×</button><h2 id="figma-enquiry-title">Product Enquiry</h2><p class="figma-enquiry-lead">Tell us about your project, and our team will get back to you shortly.</p><form><div class="figma-enquiry-grid"><input class="full" required placeholder="Name*"><input required placeholder="Mobile number*"><input type="email" required placeholder="Email id*"><select required><option value="">City*</option><option>Mumbai</option><option>Delhi NCR</option><option>Bengaluru</option><option>Other</option></select><input placeholder="Company / Firm Name"><select class="full" required><option value="">I am a*</option><option>Architect</option><option>Interior Designer</option><option>Homeowner</option><option>Other</option></select><textarea class="full" placeholder="Tell us more — Symphony IV, Symphony Collection, Pendant Light"></textarea></div><div class="figma-captcha"><i></i> I am not a robot</div><button class="figma-enquiry-submit" type="submit">SEND ENQUIRY</button><p class="figma-enquiry-consent">By submitting this form, you agree to be contacted by <em>Abby Lighting</em> regarding your enquiry.</p></form></div></div>';
 const lightbox='<div class="figma-lightbox" hidden role="dialog" aria-modal="true" aria-label="Product image viewer"><button class="figma-lightbox-close" aria-label="Close image viewer">×</button><button class="figma-lightbox-prev" aria-label="Previous image">‹</button><img alt="Symphony IV product detail"><button class="figma-lightbox-next" aria-label="Next image">›</button></div>';
 const colourVariants=[
  {name:'Black + White',finishA:'#050505',finishB:'#f4f4f1',code:'BLK-WHT',colorImageUrl:hero},
  {name:'Black + Gold',finishA:'#050505',finishB:'#efb45f',code:'BLK-GLD',colorImageUrl:'/images/decorative/symphonyiv-on.png'},
  {name:'Gold + White',finishA:'#efb45f',finishB:'#f4f4f1',code:'GLD-WHT',colorImageUrl:'/images/decorative/symphonyiv-gold-white.png'},
  {name:'Black',finishA:'#050505',finishB:'#050505',code:'BLK',colorImageUrl:'/images/decorative/symphonyiv-black-black.png'}
 ];
 const sizeVariants=[['One Size','250']];
 let galleryIndex=-1;
 let familyIndex=0;
 const defaultHero=()=>matchMedia('(max-width:600px)').matches?mobileHero:hero;
 const defaultFirstThumbnail=galleryThumbs[0];
 let colourImageRequest=0;
 const moveThumbnailTrack=()=>{const carousel=document.querySelector('.thumb-carousel');const track=document.querySelector('.product-thumbs');const buttons=[...document.querySelectorAll('.product-thumbs button')];if(!carousel||!track||!buttons.length)return;const gap=parseFloat(getComputedStyle(track).gap)||10;const step=buttons[0].offsetWidth+gap;const usable=Math.max(step,carousel.clientWidth-94);const visible=Math.max(1,Math.floor(usable/step));const current=Math.max(0,galleryIndex);const first=Math.max(0,Math.min(current-Math.floor(visible/2),buttons.length-visible));track.style.transform='translate3d('+(-first*step)+'px,0,0)'};
 const swapStage=(stage,src)=>{const current=stage.getAttribute('src');if(!current||current===src){stage.src=src;return}const outgoing=stage.cloneNode(true);outgoing.removeAttribute('data-synced');outgoing.className='stage-outgoing';stage.parentElement.insertBefore(outgoing,stage);stage.classList.add('stage-incoming');stage.src=src;requestAnimationFrame(()=>{outgoing.classList.add('is-leaving');stage.classList.add('is-arrived')});setTimeout(()=>{outgoing.remove();stage.classList.remove('stage-incoming','is-arrived')},230)};
 const setStage=(src,index)=>{const stage=document.querySelector('.product-stage>img');if(stage)swapStage(stage,src);if(Number.isInteger(index)){galleryIndex=index;[...document.querySelectorAll('.product-thumbs button')].forEach((b,i)=>b.classList.toggle('active',i===index));moveThumbnailTrack()}const current=Math.max(0,galleryIndex);const left=document.querySelector('.thumb-edge-left img');const right=document.querySelector('.thumb-edge-right img');if(left)left.src=galleryThumbs[(current-1+galleryThumbs.length)%galleryThumbs.length];if(right)right.src=galleryThumbs[(current+1)%galleryThumbs.length];};
 const applyFeaturedColourImage=variant=>{const request=++colourImageRequest;const candidate=variant?.colorImageUrl?.trim();const commit=(stageSrc,thumbSrc)=>{if(request!==colourImageRequest)return;gallery[0]=stageSrc;galleryThumbs[0]=thumbSrc;const firstThumb=document.querySelector('.product-thumbs button:first-child img');if(firstThumb)firstThumb.src=thumbSrc;setStage(stageSrc,0)};if(!candidate){commit(defaultHero(),defaultFirstThumbnail);return}commit(candidate,candidate);const probe=new Image();probe.onerror=()=>commit(defaultHero(),defaultFirstThumbnail);probe.src=candidate};
 const updateFamilyTrack=(animate=true)=>{const carousel=document.querySelector('.related-carousel');const track=document.querySelector('.related-track');const cards=[...document.querySelectorAll('.related-track>a')];if(!carousel||!track||!cards.length)return;const gap=parseFloat(getComputedStyle(track).gap)||11;const step=cards[0].getBoundingClientRect().width+gap;const perView=innerWidth<=600?2:3;familyIndex=Math.max(0,Math.min(familyIndex,cards.length-perView));track.style.setProperty('transition',animate?'transform 300ms var(--ease-out)':'none','important');track.style.transform='translate3d('+(-familyIndex*step)+'px,0,0)';const prev=document.querySelector('.related-prev');const next=document.querySelector('.related-next');if(prev){prev.disabled=familyIndex===0;prev.style.opacity=familyIndex===0?'.35':'1'}if(next){next.disabled=familyIndex>=cards.length-perView;next.style.opacity=familyIndex>=cards.length-perView?'.35':'1'}};
 const animateColourLabel=name=>{const label=document.querySelector('.inline-colour-panel>p');if(!label)return;label.classList.add('label-changing');setTimeout(()=>{label.innerHTML='COLOUR : <b>'+name.toUpperCase()+'</b>';label.classList.remove('label-changing')},120)};
 const closeColourPanel=()=>{const wrap=document.querySelector('.inline-colour-panel-wrap');const more=document.querySelector('.more-colours');if(!wrap?.classList.contains('open'))return;wrap.classList.remove('open');more?.classList.remove('active');more?.setAttribute('aria-expanded','false')};
 const initMotion=()=>{if(document.documentElement.dataset.motionBound)return;document.documentElement.dataset.motionBound='true';const nav=document.querySelector('.product-nav');const onScroll=()=>nav?.classList.toggle('header-scrolled',scrollY>80);addEventListener('scroll',onScroll,{passive:true});onScroll();
  const infoItems=[document.querySelector('.product-tag'),document.querySelector('.product-info h1'),document.querySelector('.mobile-category'),document.querySelector('.product-desc'),document.querySelector('.symphony-colour-option'),document.querySelector('.product-option:not(.symphony-colour-option)'),document.querySelector('.product-enquire'),document.querySelector('.product-help')].filter(Boolean);infoItems.forEach((item,i)=>{item.classList.add('motion-info-item');item.style.setProperty('--motion-delay',(i*45)+'ms')});
  const collection=document.querySelector('.collection-band');collection?.querySelectorAll(':scope>div>*').forEach((item,i)=>item.style.setProperty('--collection-delay',(i*50)+'ms'));
  const familySection=document.querySelector('.related-section');const cards=[...document.querySelectorAll('.related-track>a')];const numerals=['V','VI','VII','VIII','IX','X','XI','XII','XIII','XIV'];cards.forEach((card,i)=>{card.style.setProperty('--family-delay',(i*60)+'ms');const heading=card.querySelector('h3');const img=card.querySelector('img');if(heading)heading.textContent='Symphony '+numerals[i%numerals.length];if(img){img.src=family[i%family.length];img.alt=heading?.textContent||'Symphony pendant light'}});
  const footer=document.querySelector('.figma-footer');const observe=(element,className,after)=>{if(!element)return;const observer=new IntersectionObserver(entries=>{if(entries.some(entry=>entry.isIntersecting)){element.classList.add(className);observer.disconnect();after?.()}},{rootMargin:'0px 0px -100px 0px',threshold:.08});observer.observe(element)};observe(collection,'collection-inview');observe(familySection,'family-inview',()=>setTimeout(()=>familySection.classList.add('family-settled'),900));observe(footer,'footer-inview');
  document.querySelectorAll('.footer-accordion-panel').forEach(panel=>{if(panel.querySelector(':scope>.footer-accordion-panel-inner'))return;const inner=document.createElement('div');inner.className='footer-accordion-panel-inner';while(panel.firstChild)inner.append(panel.firstChild);panel.append(inner)});
  requestAnimationFrame(()=>requestAnimationFrame(()=>document.documentElement.classList.add('motion-loaded')));updateFamilyTrack(false);addEventListener('resize',()=>{moveThumbnailTrack();updateFamilyTrack(false)},{passive:true})};
 const sync=()=>{
  const stage=document.querySelector('.product-stage>img'); if(stage&&!stage.dataset.synced){stage.src=defaultHero();stage.dataset.synced='true'}
  if(stage)stage.dataset.datasheetPrimary=hero;document.documentElement.dataset.datasheetDrawing=galleryThumbs[1];
  const thumbs=[...document.querySelectorAll('.product-thumbs button')];
  galleryThumbs.forEach((src,index)=>{const img=thumbs[index]?.querySelector('img');if(img)img.src=src;thumbs[index]?.classList.toggle('active',index===galleryIndex)});
  thumbs.slice(galleryThumbs.length).forEach(button=>button.remove());
  const band=document.querySelector('.collection-band>img'); if(band&&band.getAttribute('src')!==${JSON.stringify(figmaAssets.collection)}) band.src=${JSON.stringify(figmaAssets.collection)};
  const collectionEyebrow=document.querySelector('.collection-band>div>p');if(collectionEyebrow)collectionEyebrow.remove();
  const description=document.querySelector('.product-desc'); if(description) description.textContent='A decorative lighting collection where colour, material and composition come together in beautifully curated harmony.';
  const colourLabel=document.querySelector('.symphony-colour-option>strong');if(colourLabel&&!colourLabel.dataset.figmaLabel){colourLabel.innerHTML='<span class="finish-label-desktop">COLOUR</span><span class="finish-label-mobile">FINISH</span>';colourLabel.dataset.figmaLabel='true'}
  const sizeButton=document.querySelector('.size-options button');if(sizeButton)sizeButton.textContent='One Size';
  const help=document.querySelector('.product-help');if(help){const link=help.querySelector('a');const icon=help.querySelector('svg');help.textContent='';if(icon)help.append(icon);help.append(document.createTextNode('Don’t want to wait  '));if(link){link.textContent='Talk to us immediately';help.append(link)}}
  const specBody=document.querySelector('.spec-grid>.spec-accordion:first-child .spec-body>div');if(specBody&&!specBody.dataset.figmaData){specBody.innerHTML=specBodyMarkup;specBody.dataset.figmaData='true'}
  const title=document.querySelector('.product-info h1');if(title&&!document.querySelector('.mobile-category'))title.insertAdjacentHTML('afterend','<p class="mobile-category">Pendant Light</p>');
  const carousel=document.querySelector('.thumb-carousel');if(carousel&&!carousel.querySelector('.thumb-edge-preview'))carousel.insertAdjacentHTML('beforeend','<span class="thumb-edge-preview thumb-edge-left"><img alt="Previous gallery preview"></span><span class="thumb-edge-preview thumb-edge-right"><img alt="Next gallery preview"></span>');
  if(!document.querySelector('.figma-enquiry')) document.body.insertAdjacentHTML('beforeend',modal);
  if(!document.querySelector('.figma-lightbox')) document.body.insertAdjacentHTML('beforeend',lightbox);
  setStage(stage?.getAttribute('src')||defaultHero(),galleryIndex);
  initMotion();
 };
 const paletteNames=['Retro Red','Tangerine','Amber','Marigold','Ocean Blue','Deep Teal','Aqua','Sage','Olive','Forest','Walnut','Cocoa','Terracotta','Burgundy','Brick','Blush','Warm Stone','Soft White','Pearl','Textured White','Graphite','Charcoal','Black','Silver','Metallic Gold'];
 document.addEventListener('click',e=>{
  const openColourPanel=document.querySelector('.inline-colour-panel-wrap.open');
  const moreTrigger=e.target.closest('.more-colours');
  if(openColourPanel&&!openColourPanel.contains(e.target)&&!moreTrigger)closeColourPanel();
  const datasheetButton=e.target.closest('.figma-download-buttons button,.download-row button');
  if(datasheetButton&&datasheetButton.textContent.trim().startsWith('Datasheet')){e.preventDefault();e.stopImmediatePropagation();const label=datasheetButton.textContent;datasheetButton.disabled=true;datasheetButton.textContent='Preparing PDF…';import('/assets/symphony-datasheet-vector.js?v=78').then(module=>module.generateProductDatasheet()).catch(()=>{datasheetButton.textContent='Could not prepare PDF — try again'}).finally(()=>{datasheetButton.disabled=false;if(datasheetButton.textContent==='Preparing PDF…')datasheetButton.textContent=label});return}
  const thumb=e.target.closest('.product-thumbs button');
  if(thumb){e.preventDefault();const buttons=[...thumb.parentElement.children];const index=buttons.indexOf(thumb);setStage(gallery[index],index);return}
  const galleryArrow=e.target.closest('.gallery-arrow');
  if(galleryArrow){e.preventDefault();galleryIndex=(galleryIndex+(galleryArrow.classList.contains('gallery-next')?1:-1)+gallery.length)%gallery.length;setStage(gallery[galleryIndex],galleryIndex);return}
  const relatedArrow=e.target.closest('.related-arrow');
  if(relatedArrow){e.preventDefault();familyIndex+=relatedArrow.classList.contains('related-next')?1:-1;updateFamilyTrack(e.detail!==0);return}
  const topSwatch=e.target.closest('.combination-swatches>button');
  if(topSwatch&&!topSwatch.classList.contains('more-colours')){e.preventDefault();const buttons=[...topSwatch.parentElement.querySelectorAll(':scope>button:not(.more-colours)')];buttons.forEach(b=>b.classList.toggle('active',b===topSwatch));const index=buttons.indexOf(topSwatch);const variant=colourVariants[index];applyFeaturedColourImage(variant);animateColourLabel(variant?.name||topSwatch.textContent.trim());return}
  const more=e.target.closest('.more-colours');
  if(more){e.preventDefault();const wrap=document.querySelector('.inline-colour-panel-wrap');const open=!wrap.classList.contains('open');wrap.classList.toggle('open',open);more.classList.toggle('active',open);more.setAttribute('aria-expanded',String(open));return}
  const specToggle=e.target.closest('.spec-accordion>button');
  if(specToggle){e.preventDefault();const section=specToggle.closest('.spec-accordion');const open=!section.classList.contains('open');section.classList.toggle('open',open);specToggle.setAttribute('aria-expanded',String(open));const icon=specToggle.querySelector('i');if(icon)icon.textContent=open?'−':'+';return}
  const stageButton=e.target.closest('.product-stage');
  if(stageButton){e.preventDefault();const box=document.querySelector('.figma-lightbox');box.querySelector('img').src=stageButton.querySelector('img').src;box.hidden=false;document.body.style.overflow='hidden';return}
  const lightboxClose=e.target.closest('.figma-lightbox-close');
  if(lightboxClose||e.target.classList.contains('figma-lightbox')){const box=document.querySelector('.figma-lightbox');box.hidden=true;document.body.style.overflow='';return}
  const lightboxArrow=e.target.closest('.figma-lightbox-prev,.figma-lightbox-next');
  if(lightboxArrow){galleryIndex=(galleryIndex+(lightboxArrow.classList.contains('figma-lightbox-next')?1:-1)+gallery.length)%gallery.length;setStage(gallery[galleryIndex],galleryIndex);document.querySelector('.figma-lightbox img').src=gallery[galleryIndex];return}
  const colour=e.target.closest('.colour-library div[title]');
  if(colour){const all=[...colour.parentElement.children];all.forEach(x=>x.classList.remove('selected','preview-active'));colour.classList.add('selected','preview-active');applyFeaturedColourImage(null);animateColourLabel(colour.getAttribute('title'));return}
  const footerToggle=e.target.closest('.footer-accordion-toggle');if(footerToggle){e.preventDefault();const item=footerToggle.closest('.footer-accordion');const open=!item.classList.contains('open');item.classList.toggle('open',open);footerToggle.setAttribute('aria-expanded',String(open));const panel=item.querySelector('.footer-accordion-panel');if(panel)panel.setAttribute('aria-hidden',String(!open));return}
  const emptyCard=e.target.closest('.related-track>a[href="#"]');if(emptyCard){e.preventDefault();return}
  const enquire=e.target.closest('.product-enquire');if(enquire){e.preventDefault();e.stopImmediatePropagation();const m=document.querySelector('.figma-enquiry');if(m)m.hidden=false;document.body.style.overflow='hidden';return}
  if(e.target.closest('.figma-enquiry-close')||e.target.classList.contains('figma-enquiry')){const m=document.querySelector('.figma-enquiry');if(m)m.hidden=true;document.body.style.overflow='';}
 },true);
 document.addEventListener('keydown',e=>{if(e.key==='Escape')closeColourPanel()});
 document.addEventListener('submit',e=>{if(e.target.closest('.figma-enquiry')){e.preventDefault();}},true);
 addEventListener('DOMContentLoaded',sync); addEventListener('load',sync);sync();
 addEventListener('resize',()=>{if(galleryIndex===-1){const stage=document.querySelector('.product-stage>img');if(stage)stage.src=defaultHero()}},{passive:true});
 [100,300,800,1500].forEach(delay=>setTimeout(sync,delay));
})();
</script>`;

function applyFigmaRefresh(html: string) {
  // This route began as a captured React response. Loading its stale hydration
  // payload on top of the captured markup caused clicks to reconcile against a
  // different component tree and occasionally clear the page. Keep the stable
  // server-rendered shell, remove that obsolete runtime, and bind the product
  // interactions explicitly in figmaDomSync below.
  let next = html
    .replace(/<script\b[^>]*>[\s\S]*?<\/script>/gi, "")
    .replace(/<link\b[^>]*rel=["']modulepreload["'][^>]*>/gi, "")
    .replace(/<\/html>[\s\S]*$/i, "</html>")
    .replace("</head>", `${figmaCss}</head>`);
  const galleryImages = [figmaAssets.hero, ...figmaAssets.thumbnails];
  const oldGallery = [
    "/images/decorative/symphonyiv-black-white.png",
    "/images/decorative/symphonyiv-off.png",
    "/images/decorative/symphonyiv-on.png",
    "/images/decorative/symphonyiv-gold-white.png",
    "/images/decorative/symphonyiv-black-black.png",
  ];
  oldGallery.forEach((asset, index) => {
    next = next.replaceAll(asset, galleryImages[index] ?? figmaAssets.hero);
  });
  next = next.replaceAll("/images/decorative/hero.png", figmaAssets.collection);
  ["/images/cymbal/image-03.jpg", "/images/cymbal/image-04.jpg", "/images/cymbal/image-05.jpg"].forEach((asset, index) => {
    next = next.replaceAll(asset, figmaAssets.family[index]);
  });
  next = next
    .replaceAll("A playful sculptural pendant composed through contrasting colour and refined metallic detail. Symphony IV brings a distinctive graphic character to intimate and expressive interiors.", "A decorative lighting collection where colour, material and composition come together in beautifully curated harmony.")
    .replaceAll("A decorative lighting collection where colour, texture and composition come together in beautiful central themes.", "A decorative lighting collection where colour, material and composition come together in beautifully curated harmony.")
    .replaceAll("Symphony Collection · Pendant Light", "SYMPHONY COLLECTION · PENDANT LIGHT")
    .replaceAll("Part of <em>Symphony<!-- --> Collections</em>", "Part of <em>Symphony Collection</em>")
    .replaceAll("A family of expressive pendants built around colour, rhythm and carefully balanced geometry. Symphony brings confident combinations and crafted metallic detail into contemporary interiors.", "Symphony begins with a choice, not a fixture. Mix forms, colours, and suspension options to create compositions that adapt to every space. The system provides the language. The composition is yours.")
    .replaceAll(">Symphony I</h3>", ">Symphony V</h3>")
    .replaceAll(">Symphony II</h3>", ">Symphony VI</h3>")
    .replaceAll(">Symphony III</h3>", ">Symphony VII</h3>")
    .replaceAll("Two-colour combinations", "Black, White, Copper, Black + White, Black + Gold")
    .replaceAll("<b>Material</b><span>Metal</span>", "<b>Primary Material</b><span>Aluminium</span></p><p><b>Primary Finish</b><span>Black, White, Copper, Black + White, Black + Gold</span></p><p><b>Secondary Material</b><span>Aluminium</span></p><p><b>Secondary Finish</b><span>White, Black</span></p><p><b>Wire Colour</b><span>White</span>")
    .replaceAll("<b>Wire Colour</b><span>White</span>", "<b>Wire Colour</b><span>White</span></p><p><b>Size</b><span>Onesize</span></p><p><b>Height</b><span>250 mm</span></p><p><b>Diameter</b><span>Ø 250 mm</span></p><p><b>Canopy</b><span>Ø 95 mm, H 45 mm</span></p><p><b>Wire Length</b><span>1.5 m</span></p><p><b>Light source</b><span>E27 Compatible (Bulb not included)</span></p><p><b>Input Voltage</b><span>220V-240V</span></p><p><b>Control</b><span>ON-OFF</span>")
    .replaceAll("<b>Diameter</b><span>Ø 250 mm</span>", "<b>Size</b><span>Onesize</span></p><p><b>Height</b><span>250 mm</span></p><p><b>Diameter</b><span>Ø 250 mm</span>")
    .replaceAll("<b>Height</b><span>H 250 mm</span>", "")
    .replaceAll("<b>Cable</b><span>Standard 1.5 m · Custom lengths available</span>", "<b>Wire Length</b><span>1.5 m</span>")
    .replaceAll("<b>Light source</b><span>E27 · LED compatible</span>", "<b>Light source</b><span>E27 Compatible <small>(Bulb not included)</small></span>")
    .replaceAll("<b>Colour temperature</b><span>2700–3000K warm white</span>", "")
    .replaceAll("<b>Input</b><span>220–240V AC · 50/60 Hz</span>", "<b>Input Voltage</b><span>220V–240V</span></p><p><b>Control</b><span>ON–OFF</span>");
  return next.replace("</body>", `${figmaDomSync}</body>`);
}

export async function GET() {
  return new Response(applyFigmaRefresh(symphonyHtml), {
    headers: {
      "content-type": "text/html; charset=utf-8",
      "cache-control": "no-store, max-age=0",
    },
  });
}
