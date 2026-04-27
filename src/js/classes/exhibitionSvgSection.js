// for this to work, there has to be a .svg-section element followed immediately by .svg-group elements as well as a .svg-wrapper element
export default class ExhibitionSvgSection {
  constructor(el, isLargeQuery) {
    this.isLargeQuery = isLargeQuery;
    this.el = el;
    this.resizeTimer = null;
    this.init();
  }

  init() {
    this.wrapper = this.el.querySelector(".svg-wrapper");
    this.wrapper.style.opacity = '0'
    this.groups = Array.from(document.querySelectorAll(".svg-group"));

    this.svg = document.createElementNS("http://www.w3.org/2000/svg", "svg");
    this.line = document.createElementNS("http://www.w3.org/2000/svg", "path");

    this.line.setAttribute("stroke", "white");

    this.setValues();

    this.line.setAttribute("stroke-width", this.strokeWidth);
    this.line.setAttribute("fill", "none");

    this.svg.appendChild(this.line);

    this.setSvgSize();

    this.wrapper.appendChild(this.svg);

    this.wrapper.style.opacity = '1'

    window.addEventListener("resize", () => {
      clearTimeout(this.resizeTimer);
      this.wrapper.style.opacity = '0'
      this.resizeTimer = setTimeout(() => {
        this.setValues();
        this.setSvgSize();
        this.wrapper.style.opacity = '1'
      }, 450);
    });
  }

  setSvgSize() {
    this.svg.setAttribute("width", this.wrapper.clientWidth);
    this.svg.setAttribute("height", this.wrapper.clientHeight);
  }

  setValues() {
    this.groupsData = this.groups.map((el) => {
      return {
        height: el.clientHeight,
        width: el.clientWidth
      };
    });

    this.wrapperWidth = this.wrapper.clientWidth;
    this.offset = 10;
    this.doubleOffset = this.offset * 2;
    this.strokeWidth = 1.5;
    this.halfStrokeWidth = this.strokeWidth / 2;
    this.doubleStrokeWidth = this.strokeWidth * 2;

    this.lineString = `M ${this.wrapperWidth}, 0 `;
    this.groupsData.forEach((group, index) => {
      const isEven = index % 2 === 0;

      if (index === 0) {
        // if first
        this.lineString += `h -${
          this.wrapperWidth - this.offset
        } a ${this.offset}, ${this.offset} 0 0 0 -${this.offset}, ${this.offset} v ${group.height - this.doubleOffset + this.strokeWidth} `;
      } else if (isEven) {
        // if not first and is even
        this.lineString += `a -${this.offset}, -${this.offset} 0 0 1 -${this.offset}, ${this.offset} h -${
          this.wrapperWidth - this.doubleOffset - this.strokeWidth
        } a ${this.offset}, ${this.offset} 0 0 0 -${this.offset}, ${this.offset} v ${group.height - this.doubleOffset + this.strokeWidth} `;
      } else {
        // if odd
        this.lineString += `a ${this.offset}, ${this.offset} 0 0 0 ${this.offset}, ${this.offset} h ${
          this.wrapperWidth - this.doubleOffset - this.strokeWidth
        } a ${this.offset}, ${this.offset} 0 0 1 ${this.offset}, ${this.offset} v ${group.height - this.doubleOffset + this.strokeWidth} `;
      }

      if (index + 1 === this.groupsData.length) {
        this.lineString += `a ${this.offset}, ${this.offset} 0 0 0 ${this.offset}, ${this.offset} h ${
          this.wrapperWidth - this.doubleOffset - this.doubleStrokeWidth
        } a ${this.offset}, ${this.offset} 0 0 1 ${this.offset}, ${this.offset} v 100`;
      }
    });

    this.lineString.trim();
    this.line.setAttribute("d", this.lineString);
  }
}
