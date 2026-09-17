import { symphonyDocument } from "../symphonycollection/symphony-template";
import CollectionsUI from "./collections-ui";

const mainMatch = symphonyDocument.match(/<main[^>]*>([\s\S]*)<\/main>/i);
const collectionsMarkup = mainMatch?.[1] ?? symphonyDocument;

export default function CollectionsPage() {
  return (
    <>
      <link rel="stylesheet" href="/assets/index-COqEchED.css" />
      <link rel="stylesheet" href="/assets/page-B19iijr3.css" />
      <link rel="stylesheet" href="/assets/page-Lbzks1ED.css" />
      <link rel="stylesheet" href="/assets/final-home-shell-CKTHUjwZ.css" />
      <link rel="stylesheet" href="/assets/symphonycollection.css?v=collections-1" />
      <link rel="stylesheet" href="/assets/decorative3.css?v=uploaded-source" />
      <link rel="stylesheet" href="/assets/symphony-ten-forms.css?v=uploaded-source" />
      <link rel="stylesheet" href="/assets/symphony-motion.css?v=1" />
      <link rel="stylesheet" href="/assets/symphony-hover-states.css?v=1" />
      <link rel="stylesheet" href="/assets/symphony-score-fix.css?v=1" />
      <link rel="stylesheet" href="/assets/symphony-carousel.css?v=1" />
      <link rel="stylesheet" href="/assets/symphony-figma-updates.css?v=1" />
      <link rel="stylesheet" href="/assets/symphony-colour-families.css?v=1" />
      <main dangerouslySetInnerHTML={{ __html: collectionsMarkup }} />
      <CollectionsUI />
    </>
  );
}
