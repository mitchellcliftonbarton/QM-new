module.exports = {
  purge: {
    enabled: true,
    content: ['./templates/**/*.twig'],
  },
  theme: {
    colors: {
      white: '#fff',
      black: '#000',
      transparent: 'transparent',
      'neon-green': '#b9fa05',
      'forest-green': '#2fb56a',
      purple: '#9680f9',
      grey: '#fad5e5',
      'light-grey': '#ececec',
      pink: '#fa5073',
      brown: '#bba08a',
      focusColor: 'blue',
    },
    fontFamily: {
      forma: ['Forma', 'Helvetica', 'sans-serif'],
    },
    fontSize: {
      base: ['2.5rem', '1'],
      'forma-huge': ['10rem', '1'],
      'forma-huge-mobile': ['6rem', '1'],
      'forma-h1': ['6.3rem', '1'],
      'forma-h1-mobile': ['4rem', '1'],
      'forma-h2': ['4rem', '1'],
      'forma-h2-mobile': ['3rem', '1'],
      'forma-body': ['2.1rem', '1.1'],
      'forma-body-mobile': ['2rem', '1.1'],
      'forma-caption': ['1.6rem', '1'],
      'forma-caption-mobile': ['1.4rem', '1'],
    },
    flex: {
      '0-0': '0 0 auto',
    },
    extend: {
      spacing: {
        'def-1/2': '1rem',
        def: '2rem',
        'def-1.5': '3rem',
        'def-2': '4rem',
        'def-2.5': '5rem',
      },
    },
  },
  corePlugins: {
    container: false,
  },
}
