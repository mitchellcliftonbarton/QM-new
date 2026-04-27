import { gsap, DrawSVGPlugin } from "gsap/all"
gsap.registerPlugin(DrawSVGPlugin);

// for this to work, there has to be a .svg-section element followed immediately by .svg-group elements as well as a .svg-wrapper element
export default class SvgSection {
  constructor(el, isLargeQuery) {
    this.isLargeQuery = isLargeQuery;
    this.el = el;
    this.startsAtCenter = this.el.classList.contains("start-center");
    this.isExhibition = this.el.classList.contains("svg-exhibition");
    this.resizeTimer = null;
    this.init();
  }

  init() {
    const self = this
    this.wrapper = this.el.querySelector(".svg-wrapper");
    this.wrapper.style.opacity = '0'
    this.groups = Array.from(this.el.querySelectorAll(".svg-group"));

    this.svg = document.createElementNS("http://www.w3.org/2000/svg", "svg");
    this.line = document.createElementNS("http://www.w3.org/2000/svg", "path");

    // set line color based on if a post or not
    if (document.body.classList.contains('black-bg-theme')) {
      this.line.setAttribute("stroke", "#fff");
    } else if (document.body.classList.contains('forest-green-theme')) {
      this.line.setAttribute("stroke", "#2fb56a");
    } else if (document.body.classList.contains('purple-theme')) {
      this.line.setAttribute("stroke", "#9680f9");
    } else if (document.body.classList.contains('grey-theme')) {
      this.line.setAttribute("stroke", "#fad5e5");
    } else if (document.body.classList.contains('pink-theme')) {
      this.line.setAttribute("stroke", "#fa5073");
    } else if (document.body.classList.contains('brown-theme')) {
      this.line.setAttribute("stroke", "#bba08a");
    } else if (document.body.classList.contains('green-theme') && document.querySelector('.page-wrap.single-twig')) {
      this.line.setAttribute("stroke", "#b9fa05");
    } else {
      this.line.setAttribute("stroke", "black");
    }

    this.setValues();

    this.line.setAttribute("stroke-width", this.strokeWidth);
    this.line.setAttribute("stroke-linecap", 'round')
    this.line.setAttribute("fill", "none");

    this.svg.appendChild(this.line);

    this.setSvgSize();

    this.wrapper.appendChild(this.svg);

    this.wrapper.style.opacity = '1'

    if (this.isLargeQuery) {
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

    this.elementsToObserve = Array.from(document.querySelectorAll('.observe-svg'))

    if (this.elementsToObserve.length > 0) {
      const config = { attributes: true, childList: true, subtree: true };
      
      const callback = function(mutationList, observer) {
          for(const mutation of mutationList) {
              if (mutation.type === 'childList') {
                self.reset()
              }
          }
      };

      const observer = new MutationObserver(callback);

      this.elementsToObserve.forEach(el => {
        observer.observe(el, config);
      })
    }
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

    /*---------------------------*/
    /* FOR HOME PAGE, ABOUT PAGE */
    /*---------------------------*/
    if (this.startsAtCenter) {
      this.lineString = `M ${this.wrapperWidth / 2}, 0 `;

      this.groupsData.forEach((group, index) => {
        const isEven = index % 2 === 0;

        if (index === 0) { // if first
          this.lineString += `v 40 a -${this.offset}, -${this.offset} 0 0 1 -${this.offset}, ${this.offset} h -${(this.wrapperWidth / 2) - this.doubleOffset} a ${this.offset}, ${this.offset} 0 0 0 -${this.offset}, ${this.offset} v ${group.height - this.doubleOffset - 50 + this.strokeWidth}`;
        } else if (isEven) { // if not first and is even
          this.lineString += `a -${this.offset}, -${this.offset} 0 0 1 -${this.offset}, ${this.offset} h -${this.wrapperWidth - this.doubleOffset} a ${this.offset}, ${this.offset} 0 0 0 -${this.offset}, ${this.offset} v ${group.height - this.doubleOffset + this.doubleStrokeWidth} `;
        } else { // if odd
          this.lineString += `a ${this.offset}, ${this.offset} 0 0 0 ${this.offset}, ${this.offset} h ${this.wrapperWidth - this.doubleOffset} a ${this.offset}, ${this.offset} 0 0 1 ${this.offset}, ${this.offset} v ${group.height - this.doubleOffset + this.strokeWidth} `;
        }

        if (index + 1 === this.groupsData.length) {
          if (isEven) {
            this.lineString += `a ${this.offset}, ${this.offset} 0 0 0 ${this.offset}, ${this.offset} h ${
              this.wrapperWidth - this.offset - this.strokeWidth
            }`;
          } else {
            this.lineString += `a -${this.offset}, -${this.offset} 0 0 1 -${this.offset}, ${this.offset} h -${
              this.wrapperWidth - this.offset - this.strokeWidth
            }`;
          }
        }
      });


    /*---------------------------*/
    /* FOR EXHIBITION PAGES      */
    /*---------------------------*/
    } else if (this.isExhibition) {
      this.lineString = `M ${this.wrapperWidth - this.strokeWidth}, 0 `;

      this.groupsData.forEach((group, index) => {
        const isEven = index % 2 === 0;

        if (index === 0) { // if first
          this.lineString += `v ${group.height - this.offset + this.strokeWidth} `;
        } else if (isEven) { // if not first and is even
          this.lineString += `a ${this.offset}, ${this.offset} 0 0 0 ${this.offset}, ${this.offset} h ${
            this.wrapperWidth - this.doubleOffset - this.halfStrokeWidth
          } a ${this.offset}, ${this.offset} 0 0 1 ${this.offset}, ${this.offset} v ${group.height - this.doubleOffset + this.strokeWidth} `;
        } else { // if odd
          this.lineString += `a -${this.offset}, -${this.offset} 0 0 1 -${this.offset}, ${this.offset} h -${this.wrapperWidth - this.doubleOffset - this.strokeWidth} a ${this.offset}, ${this.offset} 0 0 0 -${this.offset}, ${this.offset} v ${group.height - this.doubleOffset + this.doubleStrokeWidth} `;
        }

        if (index + 1 === this.groupsData.length) {
          if (isEven) {
            this.lineString += `a -${this.offset}, -${this.offset} 0 0 1 -${this.offset}, ${this.offset} h -${
              this.wrapperWidth - this.offset - this.strokeWidth
            }`;
          } else {
            this.lineString += `a ${this.offset}, ${this.offset} 0 0 0 ${this.offset}, ${this.offset} h ${
              this.wrapperWidth - this.offset - this.strokeWidth
            }`;
          }
        }
      });


    /*---------------------------*/
    /* DEFAULT                   */
    /*---------------------------*/
    } else {
      this.lineString = `M ${this.wrapperWidth}, 0 `;
      this.groupsData.forEach((group, index) => {
        const isEven = index % 2 === 0;

        if (index === 0) { // if first
          this.lineString += `h -${this.wrapperWidth - this.offset} a ${this.offset}, ${this.offset} 0 0 0 -${this.offset}, ${this.offset} v ${group.height - this.doubleOffset + this.strokeWidth} `;
        } else if (isEven) {
          // if not first and is even
          this.lineString += `a -${this.offset}, -${this.offset} 0 0 1 -${this.offset}, ${this.offset} h -${this.wrapperWidth - this.doubleOffset} a ${this.offset}, ${this.offset} 0 0 0 -${this.offset}, ${this.offset} v ${group.height - this.doubleOffset + this.doubleStrokeWidth + .5} `;
        } else {
          // if odd
          this.lineString += `a ${this.offset}, ${this.offset} 0 0 0 ${this.offset}, ${this.offset} h ${this.wrapperWidth - this.doubleOffset} a ${this.offset}, ${this.offset} 0 0 1 ${this.offset}, ${this.offset} v ${group.height - this.doubleOffset + this.strokeWidth} `;
        }

        if (index + 1 === this.groupsData.length) {
          if (isEven) {
            this.lineString += `a ${this.offset}, ${this.offset} 0 0 0 ${this.offset}, ${this.offset} h ${this.wrapperWidth - this.offset}`;
          } else {
            this.lineString += `a -${this.offset}, -${this.offset} 0 0 1 -${this.offset}, ${this.offset} h -${this.wrapperWidth - this.offset}`;
          }
        }
      });
    }

    this.lineString.trim();
    this.line.setAttribute("d", this.lineString);
  }

  reset() {
    this.setValues();
    this.setSvgSize();
  }
}
