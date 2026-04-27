export default class Calendar {
  constructor(isLargeQuery, svgSections) {
    this.isLargeQuery = isLargeQuery
    this.svgSections = svgSections
    this.currentMonth = document.getElementById('current-month')
    this.currentYear = document.getElementById('current-year')
    this.calendarButton = document.getElementById('calendar-button')
    this.calendarUiSection = document.getElementById('calendar-ui-section')
    this.calendarUi = document.getElementById('calendar-ui')
    this.monthSelectors = Array.from(document.querySelectorAll('.month-selector'))
    this.yearSelectors = Array.from(document.querySelectorAll('.year-selector'))
    this.monthDropdown = document.querySelector('.dropdown.month-dropdown')
    this.yearDropdown = document.querySelector('.dropdown.year-dropdown')

    this.monthItems = Array.from(document.querySelectorAll('.month-item'))

    this.init()
  }

  init() {
    this.calendarButton.addEventListener('click', (e) => {
      e.preventDefault()

      if (this.calendarUiSection.classList.contains('open')) {
        this.calendarUiSection.classList.remove('open')
        this.calendarUi.ariaExpanded = false
      } else {
        this.calendarUiSection.classList.add('open')
        this.currentMonth.focus()
        this.calendarUi.ariaExpanded = true
      }

      this.svgSections.forEach((section) => {
        section.reset()
      })
    })

    this.monthSelectors.forEach((item) => {
      item.addEventListener('click', (e) => {
        e.preventDefault()

        this.currentMonth.dataset.currentMonth = item.dataset.month
        this.currentMonth.querySelector('span').innerHTML = item.dataset.month
        this.monthSelectors.forEach((button) => button.classList.remove('active'))
        item.classList.add('active')
        this.monthDropdown.classList.remove('open')
        this.displayMonth(false)

        this.yearDropdown.querySelector('.clicker').focus() // focus year dropdown after month has been selected
      })
    })

    this.yearSelectors.forEach((item) => {
      item.addEventListener('click', (e) => {
        e.preventDefault()

        this.currentYear.dataset.currentYear = item.dataset.year
        this.currentYear.querySelector('span').innerHTML = item.dataset.year
        this.yearSelectors.forEach((button) => button.classList.remove('active'))
        item.classList.add('active')
        this.yearDropdown.classList.remove('open')
        this.displayMonth(true)
      })
    })
  }

  displayMonth(focusFirstDay) {
    const newMonthEl = this.monthItems.find((item) => {
      return (
        item.dataset.month === this.currentMonth.dataset.currentMonth &&
        item.dataset.year === this.currentYear.dataset.currentYear
      )
    })

    this.monthItems.forEach((item) => item.classList.remove('active'))

    newMonthEl.classList.add('active')

    if (focusFirstDay) {
      // focus on first day of month when closed
      const firstDay = newMonthEl.querySelector('.day-item')
      firstDay.focus()
    }
  }
}
