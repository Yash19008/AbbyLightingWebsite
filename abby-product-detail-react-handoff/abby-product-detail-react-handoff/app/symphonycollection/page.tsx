import { symphonyDocument } from "./symphony-template";

const mainMatch = symphonyDocument.match(/<main[^>]*>([\s\S]*)<\/main>/i);
const symphonyMarkup = mainMatch?.[1] ?? symphonyDocument;

export default function SymphonyCollectionPage() {
  return (
    <>
      <link rel="stylesheet" href="/assets/index-COqEchED.css" />
      <link rel="stylesheet" href="/assets/page-B19iijr3.css" />
      <link rel="stylesheet" href="/assets/page-Lbzks1ED.css" />
      <link rel="stylesheet" href="/assets/final-home-shell-CKTHUjwZ.css" />
      <link rel="stylesheet" href="/assets/symphonycollection.css?v=3" />
      <main dangerouslySetInnerHTML={{ __html: symphonyMarkup }} />
    </>
  );
}
