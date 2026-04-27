import { gsap, ScrollTrigger } from 'gsap/all'
gsap.registerPlugin(ScrollTrigger)

export default class Nav {
  constructor(isLargeQuery) {
    this.isLargeQuery = isLargeQuery
    this.el = document.getElementById('nav')
    this.focusableElements = Array.from(this.el.querySelectorAll('a[href], button, input, textarea, select'))
    this.mobileMenuButton = document.getElementById('mobile-menu-open')
    this.mobileMenu = document.getElementById('mobile-menu')
    this.mobileSublinksButton = document.getElementById('mobile-sublinks-open')
    this.navExpand = document.getElementById('nav-expand')
    this.secondaryNav = document.querySelector('.secondary-nav')
    this.resizeTimer = null

    this.defaultSecondaryHeight = 60 // approximate, i think its actually 55ish
    this.init()
  }

  init() {
    // set mobile translate select to nontabbable
    const mobileTranslateInput = document.querySelector('#mobile-menu .translate select')
    if (mobileTranslateInput) mobileTranslateInput.tabIndex = -1

    if (this.secondaryNav) this.checkSecondaryHeight()

    // this.handleFocusableElements() // open nav back up if an element gets focused by tab

    this.mobileMenuButton.addEventListener('click', (e) => {
      e.preventDefault()

      if (document.body.classList.contains('mobile-menu-open')) {
        document.body.classList.remove('mobile-menu-open')
        document.body.style.overflow = 'initial'
        if (window.scrollY < 100) {
          document.body.classList.add('mobile-sublinks-open')
        }
      } else {
        document.body.classList.remove('mobile-sublinks-open')
        document.body.classList.add('mobile-menu-open')
        document.body.style.overflow = 'hidden'
      }
    })

    if (this.mobileSublinksButton && !this.isLargeQuery) {
      this.mobileSublinksButton.addEventListener('click', (e) => {
        e.preventDefault()

        if (window.scrollY > 100) {
          if (document.body.classList.contains('mobile-sublinks-open')) {
            document.body.classList.remove('mobile-sublinks-open')
          } else {
            document.body.classList.remove('mobile-menu-open')
            document.body.style.overflow = 'initial'
            document.body.classList.add('mobile-sublinks-open')
          }
        }
      })

      ScrollTrigger.create({
        trigger: document.body,
        start: 'top top',
        end: 'top -100',
        onLeave: () => {
          document.body.classList.remove('mobile-sublinks-open')
        },
        onEnterBack: () => {
          document.body.classList.add('mobile-sublinks-open')
        },
      })
    }

    this.navExpand.addEventListener('click', (e) => {
      this.show()
    })

    window.addEventListener('resize', () => {
      clearTimeout(this.resizeTimer)
      this.resizeTimer = setTimeout(() => {
        if (this.secondaryNav) this.checkSecondaryHeight()
      }, 450)
    })

    // mobile translate functionality
    this.selectTag = document.querySelector('#mobile-menu .translate select')

    if (this.selectTag) {
      const allOptions = Array.from(this.selectTag.options)
      const currentOption = allOptions[this.selectTag.selectedIndex]
      let span = document.createElement('span')
      span.textContent = currentOption.textContent
      let styles = getComputedStyle(currentOption)
      span.style.fontFamily = styles.fontFamily
      span.style.fontStyle = styles.fontStyle
      span.style.fontWeight = styles.fontWeight
      span.style.fontSize = styles.fontSize
      document.body.appendChild(span)
      this.selectTag.style.paddingLeft = `calc(50% - ${span.offsetWidth / 2}px)`
      document.body.removeChild(span)

      this.selectTag.addEventListener('change', (e) => {
        let select = e.target
        var o = select.options[select.selectedIndex]
        var s = document.createElement('span')
        s.textContent = o.textContent
        var ostyles = getComputedStyle(o)
        s.style.fontFamily = ostyles.fontFamily
        s.style.fontStyle = ostyles.fontStyle
        s.style.fontWeight = ostyles.fontWeight
        s.style.fontSize = ostyles.fontSize
        document.body.appendChild(s)
        select.style.paddingLeft = `calc(50% - ${s.offsetWidth / 2}px)`
        document.body.removeChild(s)
      })
    }
  }

  hide() {
    this.el.classList.add('hide')
  }

  fullHide() {
    this.el.classList.add('full-hide')
  }

  show() {
    this.el.classList.remove('hide')
    this.el.classList.remove('full-hide')
  }

  checkSecondaryHeight() {
    if (this.secondaryNav.clientHeight > this.defaultSecondaryHeight) {
      this.el.classList.add('has-two-rows')
    } else {
      this.el.classList.remove('has-two-rows')
    }
  }

  // handleFocusableElements() {
  //   this.focusableElements.forEach(el => {
  //     el.addEventListener('focus', () => {
  //       if (this.el.classList.contains('hide')) this.show()
  //     })
  //   })
  // }
}
