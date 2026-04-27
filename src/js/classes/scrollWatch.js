export default class ScrollWatch {
  constructor(navInstance, state, isLargeQuery) {
    this.nav = navInstance
    this.isLargeQuery = isLargeQuery
    this.state = state
    this.lastScrollTop = 0
    this.noScrollZone = 100
    this.init()
  }

  init() {
    if (this.isLargeQuery) {
      let scrollWatch = () => {
        let st = window.pageYOffset || window.scrollY // scroll value

        if (this.state.scrollWatchInactive) {
          // console.log('scroll watch inactive')
        } else if (this.lastScrollTop < this.noScrollZone) {
          // if scrollTop is inside the noScrollZone
          this.nav.show()
        } else {
          if (this.state.scrollBackDistance) {
            if (st > this.lastScrollTop) {
              this.nav.hide()
            } else {
              if (st < this.state.scrollBackDistance) {
                this.nav.hide()
              } else {
                this.nav.show()
              }
            }
          } else {
            st > this.lastScrollTop // if current scroll value is greater than last scroll value, aka scrolling down
              ? this.nav.hide()
              : this.nav.show()
          }
        }

        this.lastScrollTop = st <= 0 ? 0 : st
      }

      window.addEventListener('scroll', scrollWatch)
    }
  }
}
