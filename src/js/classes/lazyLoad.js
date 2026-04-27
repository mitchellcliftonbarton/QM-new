export default class LazyLoad {
  constructor (isLargeQuery) {
    this.allElements = Array.from(document.querySelectorAll('.lazy-load'))
    this.observer    = null
    this.isLargeQuery = isLargeQuery
    this.options = {
      rootMargin: '0px 0px 50% 0px',
      threshold: 0
    }

    this.init()
  }

  init() {
    this.observer = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (entry.isIntersecting && entry.target.dataset.img && !entry.target.classList.contains('loaded')) {
          let image = new Image()
          
          image.src = entry.target.dataset.img
          image.onload = () => {
            entry.target.src = image.src
            entry.target.classList.add('loaded')
          }
        }

        if (entry.isIntersecting && entry.target.dataset.vidSrc && !entry.target.classList.contains('loaded')) {
          entry.target.src = entry.target.dataset.vidSrc

          entry.target.classList.add('loaded')
        }
      })
    }, this.options)

    for (var i = 0; i < this.allElements.length; i++) {
        this.observer.observe(this.allElements[i])
    }
  }
}
