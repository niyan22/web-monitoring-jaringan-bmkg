import './bootstrap'
import Alpine from 'alpinejs'

window.Alpine = Alpine
Alpine.start()

document.addEventListener('DOMContentLoaded', () => {
    console.log('✅ JavaScript Loaded Successfully')

    const html = document.documentElement
    const sidebar = document.getElementById('sidebar')
    const navbar = document.querySelector('.navbar')
    const toggleSidebar = document.getElementById('toggleSidebar')
    const toggleDark = document.getElementById('toggleDark')
    const body = document.body

    // ==== DARK MODE ====
    console.log('Initializing dark mode...')

    const isDarkStored = localStorage.getItem('darkMode') === 'true'
    if (isDarkStored) {
        console.log('Dark mode was previously enabled')
        body.classList.add('dark')
        sidebar?.classList.add('dark')
        navbar?.classList.add('dark')
    }

    if (toggleDark) {
        console.log('Dark mode button found')
        toggleDark.addEventListener('click', (e) => {
            e.preventDefault()
            e.stopPropagation()

            const isDark = body.classList.toggle('dark')
            sidebar?.classList.toggle('dark')
            navbar?.classList.toggle('dark')

            localStorage.setItem('darkMode', isDark)
            console.log('🌙 Dark mode toggled:', isDark)
        })
    } else {
        console.warn('Dark mode button NOT found')
    }

    // ==== SIDEBAR TOGGLE ====
    console.log('Initializing sidebar toggle...')

    if (toggleSidebar && sidebar) {
        toggleSidebar.addEventListener('click', (e) => {
            e.preventDefault()
            e.stopPropagation()

            sidebar.classList.toggle('collapsed')
            const isCollapsed = sidebar.classList.contains('collapsed')
            localStorage.setItem('sidebarCollapsed', isCollapsed)
            console.log('📋 Sidebar collapsed:', isCollapsed)
        })
    } else {
        console.warn('Sidebar or toggle button NOT found')
    }

    // ==== RESTORE STATE ====
    if (localStorage.getItem('sidebarCollapsed') === 'true') {
        sidebar?.classList.add('collapsed')
        console.log('Sidebar state restored')
    }
})
