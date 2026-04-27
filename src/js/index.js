import './../scss/index.scss'
import Nav from './classes/nav'
import PushNav from './classes/pushNav'
import ScrollWatch from './classes/scrollWatch'
import Hero from './classes/hero'
import Home from './classes/home'
import ScrollToLink from './classes/scrollToLink'
import ScrollToSection from './classes/scrollToSection'
import SvgSection from './classes/svgSection'
import Exhibition from './classes/exhibition'
import Dropdown from './classes/dropdown'
import BigCarousel from './classes/bigCarousel'
import Calendar from './classes/calendar'
import MiniSlider from './classes/miniSlider'
import History from './classes/history'
import ScreenSaver from './classes/screenSaver'
import InterventionFlyout from './classes/interventionFlyout'
import AudioPlayer from './classes/audioPlayer'
import Visit from './classes/visit'
import DefaultVideo from './classes/defaultVideo'
import LazyLoad from './classes/lazyLoad'

import Cookies from 'js-cookie'
import { gsap, ScrollToPlugin } from 'gsap/all'
gsap.registerPlugin(ScrollToPlugin)

document.addEventListener('DOMContentLoaded', () => {
  /*
  ----------------
  CREDITS
  ----------------
  */

  console.log('%c \nDevelopment by Cold Rice \n \ncold-rice.info \n \n', 'color: grey')

  /*
  ----------------
  GET DEVICE TYPE, LARGE QUERY
  ----------------
  */

  function getDeviceType() {
    const ua = navigator.userAgent
    if (/(tablet|ipad|playbook|silk)|(android(?!.*mobi))/i.test(ua)) {
      return 'tablet'
    } else if (
      /Mobile|Android|iP(hone|od)|IEMobile|BlackBerry|Kindle|Silk-Accelerated|(hpw|web)OS|Opera M(obi|ini)/.test(ua)
    ) {
      return 'mobile'
    }
    return 'desktop'
  }

  const device = getDeviceType()
  const isLargeQuery = window.matchMedia('(min-width: 992px)').matches
  const heros = Array.from(document.querySelectorAll('.hero'))
  const isHome = document.querySelector('.page-wrap.home')
  const isExhibition = document.querySelector('.page-wrap.single-exhibition')
  const isHistory = document.querySelector('.page-wrap.history')
  const isCalendar = document.querySelector('.page-wrap.calendar')
  const isVisit = document.querySelector('.page-wrap.visit')
  const scrollToLinks = Array.from(document.querySelectorAll('.scroll-to-link'))
  const scrollToSections = Array.from(document.querySelectorAll('.scroll-to-section'))
  const svgSections = Array.from(document.querySelectorAll('.svg-section'))
  const dropdowns = Array.from(document.querySelectorAll('.dropdown'))
  const bigCarouselEl = document.querySelector('.image-gallery')
  const backLinks = Array.from(document.querySelectorAll('.back-link'))
  const miniSliders = Array.from(document.querySelectorAll('.mini-slider'))
  const screensaverEl = document.getElementById('artist-screensaver')
  const flyoutEl = document.getElementById('artist-flyout')
  const audioPlayers = Array.from(document.querySelectorAll('.audio-player'))
  const videos = Array.from(document.querySelectorAll('.default-video'))
  const translatorMobile = document.querySelector('.translator-mobile select')
  const backToTopEls = Array.from(document.querySelectorAll('.back-to-top'))

  const allSvgSections = []
  window.scrollToSectionInstances = []

  let state = {
    scrollBackDistance: false,
    scrollWatchInactive: false,
  }

  /*
  ----------------
  PUSH NAV STUFF
  ----------------
  */

  new PushNav()

  /*
  ----------------
  CREATE NAV
  ----------------
  */

  const nav = new Nav(isLargeQuery)

  /*
  ----------------
  HISTORY PAGE - This needs to be before scroll wtach
  ----------------
  */

  if (isHistory) new History(isLargeQuery, nav, state)

  /*
  ----------------
  VISIT PAGE
  ----------------
  */

  if (isVisit) new Visit()

  /*
  ----------------
  SCROLL WATCH STUFF
  ----------------
  */

  new ScrollWatch(nav, state, isLargeQuery)

  /*
  ----------------
  HERO STUFF
  ----------------
  */

  if (heros.length > 0) {
    heros.forEach((el) => {
      new Hero(el)
    })
  }

  /*
  ----------------
  HOME PAGE
  ----------------
  */

  if (isHome) new Home()

  /*
  ----------------
  EXHIBITION PAGE
  ----------------
  */

  if (isExhibition) new Exhibition(isLargeQuery)

  /*
  ----------------
  CALENDAR PAGE
  ----------------
  */

  if (isCalendar) new Calendar(isLargeQuery, allSvgSections)

  /*
  ----------------
  IMAGE GALLERIES
  ----------------
  */

  if (bigCarouselEl) new BigCarousel(isLargeQuery)

  /*
  ----------------
  SCREENSAVER
  ----------------
  */

  if (screensaverEl) new ScreenSaver()

  /*
  ----------------
  INTERVENTION FLYOUT
  ----------------
  */

  if (flyoutEl) new InterventionFlyout(nav)

  /*
  ----------------
  MINI SLIDERS
  ----------------
  */

  if (miniSliders.length > 0) {
    miniSliders.forEach((el) => {
      new MiniSlider(isLargeQuery, el)
    })
  }

  /*
  ----------------
  DEFAULT VIDEOS
  ----------------
  */

  if (videos.length > 0) {
    videos.forEach((el) => {
      new DefaultVideo(el)
    })
  }

  /*
  ----------------
  SCROLL TO LINKS AND SECTIONS
  ----------------
  */

  if (scrollToSections.length > 0) {
    scrollToSections.forEach((section) => {
      window.scrollToSectionInstances.push(new ScrollToSection(section, scrollToLinks))
    })
  }

  if (scrollToLinks.length > 0) {
    scrollToLinks.forEach((link) => {
      new ScrollToLink(link, state, isLargeQuery)
    })
  }

  /*
  ----------------
  DROPDOWNS
  ----------------
  */

  if (dropdowns.length > 0) {
    dropdowns.forEach((el) => {
      new Dropdown(el)
    })
  }

  /*
  ----------------
  SVG SECTIONS
  ----------------
  */

  window.addEventListener('load', () => {
    svgSections.forEach((el) => {
      const section = new SvgSection(el, isLargeQuery)
      allSvgSections.push(section)
    })
  })

  /*
  ----------------
  BACK LINKS
  ----------------
  */

  backLinks.forEach((link) => {
    const stringChecker = link.dataset.stringCheck

    if (!document.referrer.includes(stringChecker)) {
      Cookies.set('back-link', document.referrer, { expires: 0.5 })
    }

    const backValue = Cookies.get('back-link')
    if (backValue) {
      link.setAttribute('href', backValue)
    } else {
      link.setAttribute('href', '/')
    }
  })

  /*
  ----------------
  AUDIO PLAYERS
  ----------------
  */

  audioPlayers.forEach((el) => {
    new AudioPlayer(el)
  })

  /*
  ----------------
  TRANSLATOR MOBILE
  ----------------
  */
  if (translatorMobile) {
    const allOptions = Array.from(translatorMobile.options)
    const currentOption = allOptions[translatorMobile.selectedIndex]
    let span = document.createElement('span')
    span.textContent = currentOption.textContent
    let styles = getComputedStyle(currentOption)
    span.style.fontFamily = styles.fontFamily
    span.style.fontStyle = styles.fontStyle
    span.style.fontWeight = styles.fontWeight
    span.style.fontSize = styles.fontSize
    document.body.appendChild(span)
    translatorMobile.style.paddingLeft = `calc(50% - ${span.offsetWidth / 2}px)`
    document.body.removeChild(span)

    translatorMobile.addEventListener('change', (e) => {
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

  /*
  ----------------
  BREADCRUMBS
  ----------------
  */

  const breadcrumbTitle = document.getElementById('breadcrumb-title')
  const page = document.querySelector('.page-wrap')
  const shouldBreadcrumb = page.dataset.breadcrumb === 'true'

  if (breadcrumbTitle && shouldBreadcrumb) {
    const referrer = document.referrer
    let referrerPage = false

    if (referrer.includes(window.location.host)) {
      if (referrer.includes('/whats-on')) {
        referrerPage = {
          title: "What's On",
          link: '/whats-on',
        }
      } else if (referrer.includes('/calendar')) {
        referrerPage = {
          title: 'Calendar',
          link: '/calendar',
        }
      } else if (referrer.includes('/learn')) {
        referrerPage = {
          title: 'Learn',
          link: '/learn',
        }
      } else if (referrer.includes('/engage')) {
        referrerPage = {
          title: 'Engage',
          link: '/engage',
        }
      } else if (referrer.includes('/about')) {
        referrerPage = {
          title: 'About',
          link: '/about',
        }
      } else if (referrer.includes('/visit')) {
        referrerPage = {
          title: 'Visit',
          link: '/visit',
        }
      } else if (referrer.includes('/support')) {
        referrerPage = {
          title: 'Support',
          link: '/support',
        }
      } else if (referrer.includes('/exhibitions/past')) {
        referrerPage = {
          title: 'Past Exhibitions and Projects Archive',
          link: '/exhibitions/past',
        }
      } else if (referrer.includes('/programs/archive')) {
        referrerPage = {
          title: 'Past Programs Archive',
          link: '/programs/archive',
        }
      }
    }

    if (referrerPage) {
      breadcrumbTitle.innerHTML = referrerPage.title
      breadcrumbTitle.setAttribute('href', referrerPage.link)
    }

    breadcrumbTitle.style.opacity = '1'
  } else if (breadcrumbTitle && !shouldBreadcrumb) {
    breadcrumbTitle.style.opacity = '1'
  }

  backToTopEls.forEach((el) => {
    el.addEventListener('click', (e) => {
      e.preventDefault()

      gsap.to(window, {
        scrollTo: {
          duration: 0.5,
          y: 0,
        },
      })
    })
  })

  /*
  ----------------
  LAZY LOAD
  ----------------
  */

  new LazyLoad(isLargeQuery) // running after because of carousel on exhibition page
})
