import { gsap, ScrollTrigger, DrawSVGPlugin, ScrollToPlugin } from "gsap/all";
gsap.registerPlugin(ScrollTrigger, DrawSVGPlugin, ScrollToPlugin);

export default class History {
  constructor(isLargeQuery, navInstance, state) {
    this.isLargeQuery = isLargeQuery
    this.navInstance = navInstance
    this.state = state
    this.tl = null
    this.highlightLinks = Array.from(document.querySelectorAll('.highlight-link'))
    this.imageContainer = document.querySelector('.highlight-images .inner')
    this.texts = Array.from(document.querySelectorAll('.highlight-text'))
    this.wrapper = document.getElementById('history-svg')
    this.resizeTimer = null;
    this.distance = 2000
    this.init();
  }

  init() {
    this.setupSvg()

    this.tl = gsap.timeline({
      // yes, we can add it to an entire timeline!
      scrollTrigger: {
        trigger: ".history-hero",
        pin: true,   // pin the trigger element while active
        start: "top top", // when the top of the trigger hits the top of the viewport
        end: `+=${this.highlightLinks.length * this.distance}`,
        scrub: true, // smooth scrubbing, takes 1 second to "catch up" to the scrollbar
      }
    });

    this.tl.set(this.line, { drawSVG: '0%' })
    this.tl.set(this.texts[0].querySelector('h2'), { opacity: 1, y: 0})

    const self = this

    this.highlightLinks.forEach((link, index) => {
      const title = this.texts[index].querySelector('h2')
      const text = this.texts[index].querySelector('.text')
      let totalDuration = 0
      
      index === 0
        ? this.tl.to(title, { opacity: 1, y: 0, duration: 1 })
        : this.tl.fromTo(title, { opacity: 0, y: -20, duration: 1 }, { opacity: 1, y: 0 })
      totalDuration += 1
      if (text) {
        this.tl.fromTo(text, { opacity: 0, y: -20, duration: 1 }, { opacity: 1, y: 0 })
        totalDuration += 1
      }
      this.tl.addLabel(link.dataset.title)
      
      if (index !== this.highlightLinks.length - 1) {
        this.tl.to(this.imageContainer, { x: `-${100 * (index + 1)}%`, duration: 2 })
        this.tl.to(title, { opacity: 0, y: 20, duration: 1 })
        totalDuration += 3
        if (text) {
          this.tl.to(text, { opacity: 0, y: 20, duration: 1 })
          totalDuration += 1
        }
      }

      this.tl.to(this.line, { drawSVG: `${((index + 1) / self.highlightLinks.length) * 100}%`, ease: 'linear', duration: totalDuration }, `>-${totalDuration * .9}`)

      link.addEventListener('click', e => {
        e.preventDefault()

        var st = this.tl.scrollTrigger
        var pos = st.start + (st.end - st.start) * (this.tl.labels[link.dataset.title] / this.tl.duration())
        gsap.to(window, {scrollTo: pos, duration: 1})
      })
    })

    this.tl.fromTo(this.line2, { drawSVG: '0%' }, { duration: 1, drawSVG: '100%', ease: 'linear' })
    
    this.state.scrollBackDistance = (this.highlightLinks.length * this.distance) + window.innerHeight

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

  setupSvg() {
    this.wrapper.style.opacity = '0'
    this.svg = document.createElementNS("http://www.w3.org/2000/svg", "svg");
    this.line = document.createElementNS("http://www.w3.org/2000/svg", "path");
    this.line2 = document.createElementNS("http://www.w3.org/2000/svg", "path");
    this.line.setAttribute("stroke", "white");
    this.line2.setAttribute("stroke", "white");
    
    this.setValues()

    this.line.setAttribute("stroke-width", this.strokeWidth);
    this.line.setAttribute("fill", "none");
    this.line2.setAttribute("stroke-width", this.strokeWidth);
    this.line2.setAttribute("fill", "none");

    this.svg.appendChild(this.line);
    this.svg.appendChild(this.line2);

    this.setSvgSize()

    this.wrapper.appendChild(this.svg);

    this.wrapper.style.opacity = '1'
  }

  setValues() {
    this.wrapperWidth = this.wrapper.clientWidth
    this.wrapperHeight = this.wrapper.clientHeight
    this.offset = 20;
    this.doubleOffset = this.offset * 2;
    this.strokeWidth = 1.5;
    this.halfStrokeWidth = this.strokeWidth / 2;
    this.doubleStrokeWidth = this.strokeWidth * 2;

    this.lineString = `M 0, ${this.strokeWidth} h ${this.wrapperWidth - this.offset - this.strokeWidth} `;
    this.lineString2 = `M ${this.wrapperWidth - this.offset - this.strokeWidth}, 2 a 20, 20 0 0 1 20, 20 v ${this.wrapperHeight}`;

    this.lineString.trim();
    this.lineString2.trim();
    this.line.setAttribute("d", this.lineString);
    this.line2.setAttribute("d", this.lineString2);
  }
  
  setSvgSize() {
    this.svg.setAttribute("width", this.wrapper.clientWidth);
    this.svg.setAttribute("height", this.wrapper.clientHeight);
  }
}
