import Swiper from 'swiper/bundle'
import 'swiper/swiper-bundle.css'

export default class MiniSlider {
  constructor(isLargeQuery, el) {
    this.isLargeQuery = isLargeQuery

    this.carousel = null
    this.el = el
    this.carouselEl = this.el.querySelector('.swiper')
    this.captionEl = document.querySelector(`.${this.el.dataset.captionEl}`)
    this.slides = null
    this.init()
  }

  init() {
    const self = this

    this.carousel = new Swiper(this.carouselEl, {
      loop: true,
      slidesPerView: 1,
      spaceBetween: 0,
      initialSlide: 0,
      speed: 300,
      allowTouchMove: !this.isLargeQuery,
      init: true,
      pagination: {
        el: '.swiper-pagination',
        clickable: true,
      },
      on: {
        beforeInit: function () {
          self.slides = Array.from(self.carouselEl.querySelectorAll('.swiper-slide'))

          self.slides.forEach((slide) => {
            slide.addEventListener('click', (e) => {
              e.preventDefault()

              this.slideNext()
            })
          })
        },
        slideChange: function () {
          const currentSlide = self.slides[this.realIndex]

          if (currentSlide.dataset.caption) {
            self.captionEl.innerHTML = currentSlide.dataset.caption
            self.captionEl.style.opacity = '1'
            self.captionEl.style.pointerEvents = 'auto'
          } else {
            self.captionEl.style.opacity = '0'
            self.captionEl.style.pointerEvents = 'none'
            self.captionEl.innerHTML = '---'
          }
        },
      },
    })
  }
}
