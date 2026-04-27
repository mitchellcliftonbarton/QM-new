import Swiper from 'swiper/bundle'
import 'swiper/swiper-bundle.css'

export default class BigCarousel {
  constructor(isLargeQuery) {
    this.isLargeQuery = isLargeQuery;

    this.carousel = null
    this.carouselContainer = document.getElementById('image-gallery-carousel')
    this.carouselClickers = Array.from(document.querySelectorAll('.image-gallery .image'))
    this.carouselEl = this.carouselContainer.querySelector('.carousel.swiper-container')
    this.carouselCloser = document.getElementById('image-gallery-carousel-close')
    this.prev = this.carouselContainer.querySelector('.buttons #prev')
    this.next = this.carouselContainer.querySelector('.buttons #next')
    this.currentIndexEl = this.carouselContainer.querySelector('#current-index')
    this.hasMultiple = this.prev && this.next
    this.noSlide = this.carouselContainer.dataset.noSlide === 'true'

    this.clickedEl = null
    this.init();
  }

  init() {
    const self = this

    this.carousel = new Swiper(this.carouselEl, {
      loop: true,
      slidesPerView: 1,
      spaceBetween: 0,
      initialSlide: 0,
      observer: true,
      observeParents: true,
      observeSlideChildren: true,
      speed: 300,
      allowTouchMove: false,
      init: true,
      allowSlideNext: !this.noSlide,
      allowSlidePrev: !this.noSlide,
      on: {
        afterInit: function () {
          if (self.hasMultiple) {
            self.prev.addEventListener('click', e => {
              e.preventDefault()

              this.slidePrev()
            })

            self.next.addEventListener('click', e => {
              e.preventDefault()

              this.slideNext()
            })
          }

          const slides = Array.from(self.carouselEl.querySelectorAll('.swiper-slide'))

          slides.forEach(slide => {
            slide.addEventListener('click', e => {
              e.preventDefault()

              this.slideNext()
            })
          })
        },
        slideChange: function () {
          self.currentIndexEl.innerHTML = this.realIndex + 1
        }
      }
    })

    this.carouselClickers.forEach(item => {
      item.addEventListener('click', e => {
        e.preventDefault()

        this.clickedEl = item

        const index = parseInt(item.dataset.index)
        this.carousel.slideToLoop(index, 0, false) // slide with no transition

        this.currentIndexEl.innerHTML = this.carousel.realIndex + 1

        this.carouselContainer.classList.add('active')
        document.body.style.overflow = 'hidden'

        if (this.hasMultiple) {
          this.prev.tabIndex = 0
          this.next.tabIndex = 0
        }
        this.carouselCloser.tabIndex = 0

        this.carouselContainer.ariaExpanded = true

        if (this.hasMultiple) this.prev.focus()
      })
    })

    this.carouselCloser.addEventListener('click', e => {
      e.preventDefault()

      this.close()
    })
  }

  close() {
    if (this.hasMultiple) {
      this.prev.tabIndex = -1
      this.next.tabIndex = -1
    }
    this.carouselCloser.tabIndex = -1

    this.carouselContainer.classList.remove('active')
    document.body.style.overflow = 'initial'

    this.carouselContainer.ariaExpanded = false

    this.clickedEl.focus()
  }

  hasTabLeftDropdown(e) {
    if (!this.carouselContainer.contains(e.relatedTarget)) {
      this.close()
    }
  }
}
