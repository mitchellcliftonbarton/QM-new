import ExhibitionSvgSection from "./exhibitionSvgSection"

export default class Exhibition {
  constructor(isLargeQuery) {
    this.svgEl = document.querySelector('.hero-exhibition .hero-content .exhibition-svg-section')
    this.isLargeQuery = isLargeQuery;
    this.init();
  }

  init() {
    if (this.svgEl) new ExhibitionSvgSection(this.svgEl, this.isLargeQuery)
  }
}
