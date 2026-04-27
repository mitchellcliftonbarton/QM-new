export default class Dropdown {
  constructor (el) {
    this.el = el
    this.items = Array.from(this.el.querySelectorAll('.dropdown-item'))
    this.init()
  }

  init () {
    this.clicker = this.el.querySelector('.clicker')
    this.content = this.el.querySelector('.content')

    this.clicker.addEventListener('click', e => {
      e.preventDefault()

      if (this.el.classList.contains('open')) {
        this.close()
      } else {
        this.open()
      }
    })

    // this.clicker.addEventListener('blur', e => {
    //   this.hasTabLeftDropdown(e)
    // })

    // this.items.forEach(item => {
    //   item.addEventListener('blur', e => {
    //     this.hasTabLeftDropdown(e)
    //   })
    // })
  }

  close() {
    this.items.forEach(item => item.tabIndex = -1) // make items in dropdown untabbable
    this.el.classList.remove('open')
    this.el.ariaExpanded = false
  }

  open() {
    this.items.forEach(item => item.tabIndex = 0) // make items in dropdown tabbable
    this.el.classList.add('open')
    this.el.ariaExpanded = true
  }

  hasTabLeftDropdown(e) {
    if (!this.el.contains(e.relatedTarget)) {
      this.close()
    }
  }
}
