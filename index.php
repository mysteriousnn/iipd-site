<!DOCTYPE html>
<html lang="ru">
<head>
    
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-HKF6P1HJ9Q"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-HKF6P1HJ9Q');
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate, max-age=0">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="0">
<meta http-equiv="Expires" content="Tue, 01 Jan 1980 1:00:00 GMT">
    
    <title>Ищу интернет подругу/друга (WEB)</title>
    <link rel="icon" href="iipdPin.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        
        :root {
            
            --bg-primary: #1a1a1a;
            --bg-secondary: #262626;
            --bg-accent: rgba(38, 38, 38, 0.98);
            --text-primary: #ffffff;
            --text-secondary: rgba(255, 255, 255, 0.85);
            --accent-gradient: linear-gradient(135deg, #888, #aaa);
            --accent-orange: linear-gradient(135deg, #999, #bbb);
            --border-color: rgba(255, 255, 255, 0.08);
            --shadow-color: rgba(0, 0, 0, 0.4);
            --header-height: 70px;
            --menu-border-radius: 15px;
            --scrollbar-track: rgba(255, 255, 255, 0.05);
            --scrollbar-thumb: rgba(136, 136, 136, 0.5);
            --scrollbar-thumb-hover: rgba(136, 136, 136, 0.7);
            --theme-toggle-color: #ffffff;
            --theme-toggle-bg: rgba(255, 255, 255, 0.05);
            --orange-color: #ff5c20; 
        }
        
        .light-theme {
            
            --bg-primary: #f5f5f7;
            --bg-secondary: #ffffff;
            --bg-accent: rgba(255, 255, 255, 0.98);
            --text-primary: #1a1a1a;
            --text-secondary: rgba(26, 26, 26, 0.85);
            --accent-gradient: linear-gradient(135deg, #666, #888);
            --accent-orange: linear-gradient(135deg, #777, #999);
            --border-color: rgba(0, 0, 0, 0.12);
            --shadow-color: rgba(0, 0, 0, 0.1);
            --scrollbar-track: rgba(0, 0, 0, 0.05);
            --scrollbar-thumb: rgba(136, 136, 136, 0.3);
            --scrollbar-thumb-hover: rgba(136, 136, 136, 0.5);
            --theme-toggle-color: #1a1a1a;
            --theme-toggle-bg: rgba(0, 0, 0, 0.08);
            
            --menu-button-bg: rgba(0, 0, 0, 0.12);
            --menu-button-border: rgba(0, 0, 0, 0.18);
            --menu-button-text: #1a1a1a;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-tap-highlight-color: transparent;
        }
        
        html {
            scroll-behavior: auto;
            overflow: hidden;
            height: 100%;
        }
        
        body {
            background-color: var(--bg-primary);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text-primary);
            padding-top: var(--header-height);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
            line-height: 1.6;
            height: 100vh;
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
            display: flex;
            flex-direction: column;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            
        }
        
        .channel-description,
        .channel-text,
        .info-text,
        .button-description,
        .subtitle {
            -webkit-user-select: text;
            -moz-user-select: text;
            -ms-user-select: text;
            user-select: text;
        }
        
        
        .header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: var(--header-height);
            z-index: 3000;
            background-color: rgba(26, 26, 26, 0.95);
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.6);
            padding: 10px 0;
            
        }
        
        .light-theme .header {
            background-color: rgba(245, 245, 247, 0.95);
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.1);
        }

.container {
    width: 100%;
    max-width: 100%; 
    margin: 0 auto;
    padding: 0 20px;
    height: 100%;
}
        
        .main-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            height: 100%;
        }
        
        
        .logo {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            text-decoration: none;
            cursor: pointer;
            z-index: 1002;
            user-select: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 0;
        }
        
        .logo:hover {
            opacity: 0.8;
        }
        
        .logo-icon {
            font-size: 1.8rem;
            color: var(--text-primary);
        }
        
        .logo-text {
            font-family: 'Segoe UI', 'Arial', sans-serif;
            font-weight: 700;
            font-size: 1.8rem;
            letter-spacing: 0.5px;
            color: var(--text-primary);
        }
        
        .light-theme .logo-icon,
        .light-theme .logo-text {
            color: var(--text-primary);
        }
        
        
        .theme-toggle {
            position: absolute;
            right: 80px; 
            top: 50%;
            transform: translateY(-50%);
            width: 44px;
            height: 44px;
            background: var(--theme-toggle-bg);
            border: 1px solid var(--border-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--theme-toggle-color);
            cursor: pointer;
            text-decoration: none;
            z-index: 1002;
            -webkit-touch-callout: none;
        }
        
        .theme-toggle:hover {
            background: rgba(255, 255, 255, 0.1);
            color: var(--text-primary);
            border-color: rgba(255, 255, 255, 0.2);
        }
        
        .light-theme .theme-toggle:hover {
            background: rgba(0, 0, 0, 0.15);
            border-color: rgba(0, 0, 0, 0.25);
        }
        
        .theme-toggle i {
            font-size: 1.2rem;
        }
        
        
        .theme-toggle .fa-moon {
            display: block;
        }
        
        .theme-toggle .fa-sun {
            display: none;
        }
        
        
        .light-theme .theme-toggle .fa-moon {
            display: none;
        }
        
        .light-theme .theme-toggle .fa-sun {
            display: block;
        }
        
        .telegram-icon {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            width: 44px;
            height: 44px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-secondary);
            cursor: pointer;
            text-decoration: none;
            z-index: 1002;
            -webkit-touch-callout: none;
        }
        
        .telegram-icon:hover {
            background: rgba(255, 255, 255, 0.1);
            color: var(--text-primary);
            border-color: rgba(255, 255, 255, 0.2);
        }
        
        .light-theme .telegram-icon {
            background: rgba(0, 0, 0, 0.05);
            color: rgba(26, 26, 26, 0.85);
        }
        
        .light-theme .telegram-icon:hover {
            background: rgba(0, 0, 0, 0.1);
            color: var(--text-primary);
            border-color: rgba(0, 0, 0, 0.2);
        }
        
        .telegram-icon i {
            font-size: 1.3rem;
        }
        
        .menu-toggle {
            background-color: var(--bg-secondary);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            padding: 12px 24px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
            user-select: none;
            z-index: 1002;
            position: relative;
            top: 2px;
            border: none;
            outline: none;
            -webkit-tap-highlight-color: transparent;
            -webkit-touch-callout: none;
        }
        
        
        .light-theme .menu-toggle {
            background-color: var(--menu-button-bg);
            border-color: var(--menu-button-border);
            color: var(--menu-button-text);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }
        
        button, 
        .menu-toggle,
        .dropdown-toggle,
        .button-link,
        .nav-link,
        .dropdown-item {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
            -webkit-tap-highlight-color: transparent;
        }
        
        .menu-toggle:active,
        .dropdown-toggle:active,
        .button-link:active,
        .nav-link:active,
        .dropdown-item:active,
        .telegram-icon:active,
        .theme-toggle:active {
            background-color: rgba(255, 255, 255, 0.15) !important;
        }
        
        .light-theme .menu-toggle:active,
        .light-theme .dropdown-toggle:active,
        .light-theme .button-link:active,
        .light-theme .nav-link:active,
        .light-theme .dropdown-item:active,
        .light-theme .telegram-icon:active,
        .light-theme .theme-toggle:active {
            background-color: rgba(0, 0, 0, 0.15) !important;
        }
        
        .nav-list {
            display: none;
            flex-direction: column;
            width: calc(100% - 40px);
            max-width: 400px;
            position: fixed;
            top: calc(var(--header-height) + 15px);
            left: 50%;
            transform: translateX(-50%);
            background-color: var(--bg-secondary);
            padding: 0;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.7);
            border: 1px solid var(--border-color);
            border-radius: var(--menu-border-radius);
            z-index: 1001;
            max-height: 85vh;
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
            overscroll-behavior: contain;
            list-style: none;
        }
        
        .light-theme .nav-list {
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }
        
        .nav-list::-webkit-scrollbar {
            width: 8px;
        }
        
        .nav-list::-webkit-scrollbar-track {
            background: var(--scrollbar-track);
            border-radius: 4px;
            margin: 8px 0;
        }
        
        .nav-list::-webkit-scrollbar-thumb {
            background: var(--scrollbar-thumb);
            border-radius: 4px;
            border: 2px solid var(--bg-secondary);
        }
        
        .nav-list::-webkit-scrollbar-thumb:hover {
            background: var(--scrollbar-thumb-hover);
        }
        
        .nav-list {
            scrollbar-width: thin;
            scrollbar-color: var(--scrollbar-thumb) var(--scrollbar-track);
        }
        
        .nav-list.active {
            display: flex;
        }
        
        .nav-item {
            position: relative;
            width: 100%;
        }
        
        .nav-item:first-child .nav-link {
            border-radius: var(--menu-border-radius) var(--menu-border-radius) 0 0;
        }
        
        .nav-footer {
            background-color: rgba(0, 0, 0, 0.2);
            padding: 20px;
            border-top: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
            border-radius: 0 0 var(--menu-border-radius) var(--menu-border-radius);
            flex-shrink: 0;
        }
        
        .light-theme .nav-footer {
            background-color: rgba(0, 0, 0, 0.05);
        }
        
        .nav-footer-text {
            font-size: 0.9rem;
            color: var(--text-secondary);
            text-align: center;
            line-height: 1.5;
        }
        
        .nav-footer-link {
            color: #aaa;
            text-decoration: none;
            font-weight: 600;
            border-bottom: 1px solid transparent;
        }
        
        .nav-footer-link:hover {
            color: var(--text-primary);
            border-bottom: 1px solid var(--text-primary);
        }
        
        .nav-footer-year {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.85rem;
            margin-top: 5px;
        }
        
        .light-theme .nav-footer-year {
            color: rgba(26, 26, 26, 0.6);
        }
        
        .nav-item:last-child .nav-link {
            border-radius: 0;
        }
        
        .nav-link {
            color: var(--text-secondary);
            text-decoration: none;
            padding: 18px 24px;
            font-weight: 600;
            font-size: 1rem;
            border: none;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            width: 100%;
            background-color: transparent;
            position: relative;
            cursor: pointer;
            -webkit-tap-highlight-color: transparent;
            -webkit-touch-callout: none;
            user-select: none;
            flex-shrink: 0;
        }
        
        .nav-link i {
            font-size: 1.1rem;
            margin-right: 12px;
            width: 24px;
            text-align: center;
        }
        
        .link-text {
            flex-grow: 1;
            text-align: left;
        }
        
        .dropdown-menu {
            width: 100%;
            background-color: var(--bg-secondary);
            padding: 0;
            margin: 0;
            display: none;
            overflow: hidden;
            flex-shrink: 0;
        }
        
        .dropdown.active .dropdown-menu {
            display: block;
        }
        
        .nav-item.dropdown:nth-last-child(3) .nav-link {
            border-radius: 0;
        }
        
        .nav-item.dropdown:nth-last-child(3) .dropdown-menu {
            border-radius: 0;
        }
        
        .nav-item.dropdown:nth-last-child(2) .nav-link {
            border-radius: 0;
        }
        
        .nav-item.dropdown:nth-last-child(2) .dropdown-menu {
            border-radius: 0;
        }
        
        .dropdown-item {
            display: flex;
            align-items: center;
            padding: 16px 48px;
            color: var(--text-secondary);
            text-decoration: none;
            position: relative;
            min-height: 48px;
            justify-content: flex-start;
            cursor: pointer;
            -webkit-tap-highlight-color: transparent;
            -webkit-touch-callout: none;
            user-select: none;
            flex-shrink: 0;
        }
        
        .dropdown-item:last-child {
            border-radius: 0;
        }
        
        .dropdown-item::before {
            content: '';
            position: absolute;
            left: 32px;
            top: 50%;
            transform: translateY(-50%) rotate(45deg);
            width: 8px;
            height: 8px;
            background: #888;
            flex-shrink: 0;
        }
        
        .dropdown-item-text {
            flex: 1;
            padding-left: 8px;
            text-align: left;
        }
        
        .dropdown-item::after {
            content: ' ➥';
            color: #888;
            font-weight: bold;
            margin-left: auto;
        }
        
        .dropdown-toggle::after {
            content: '';
            display: inline-block;
            width: 8px;
            height: 8px;
            border-right: 2px solid currentColor;
            border-bottom: 2px solid currentColor;
            transform: rotate(45deg);
            margin-left: 12px;
            flex-shrink: 0;
        }
        
        .dropdown.active .dropdown-toggle::after {
            transform: rotate(-135deg);
        }
        
        .nav-link:hover, 
        .nav-link.active,
        .dropdown.active .dropdown-toggle {
            background-color: rgba(255, 255, 255, 0.08);
            color: var(--text-primary);
        }
        
        .light-theme .nav-link:hover, 
        .light-theme .nav-link.active,
        .light-theme .dropdown.active .dropdown-toggle {
            background-color: rgba(0, 0, 0, 0.08);
        }
        
        .dropdown-item:hover {
            background-color: rgba(255, 255, 255, 0.08);
            color: var(--text-primary);
        }
        
        .light-theme .dropdown-item:hover {
            background-color: rgba(0, 0, 0, 0.08);
        }
        
        
        .content {
            padding: 20px 0 40px 0;
            flex: 1 0 auto;
            width: 100%;
        }
        
        
        .channel-description {
            background-color: var(--bg-secondary);
            border-radius: var(--menu-border-radius);
            padding: 30px;
            margin-bottom: 30px;
            border: 1px solid var(--border-color);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
        }
        
        .light-theme .channel-description {
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }
        
        .channel-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 15px;
            color: var(--text-primary);
            text-align: center;
        }
        
        .channel-text {
            font-size: 1.05rem;
            line-height: 1.6;
            margin-bottom: 15px;
            text-align: center;
            color: var(--text-secondary);
        }
        
        .channel-text:last-child {
            margin-bottom: 0;
        }
        
       .subtitle {
    font-size: 1.1rem;
    color: var(--text-secondary);
    width: 100%;
    margin-bottom: 40px;
    text-align: center;
    background-color: var(--bg-secondary);
    padding: 30px;
    border-radius: var(--menu-border-radius);
    border: 1px solid var(--border-color);
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
}
        
        
        .load-level-section {
            position: relative;
            background-color: var(--bg-secondary);
            border-radius: var(--menu-border-radius);
            padding: 35px 30px;
            margin-bottom: 40px;
            border: 1px solid var(--border-color);
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.4);
            overflow: hidden;
        }
        
        .light-theme .load-level-section {
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.1);
        }

        
        .load-level-section.red-level {
            border-left: 5px solid #c45c5a;
            --level-color: #c45c5a;
            --level-bg: linear-gradient(135deg, rgba(196, 92, 90, 0.1), rgba(196, 92, 90, 0.05));
        }

        .load-level-section.yellow-level {
            border-left: 5px solid #ecc33c;
            --level-color: #ecc33c;
            --level-bg: linear-gradient(135deg, rgba(236, 195, 60, 0.1), rgba(236, 195, 60, 0.05));
        }

        .load-level-section.green-level {
            border-left: 5px solid #a6bb7c;
            --level-color: #a6bb7c;
            --level-bg: linear-gradient(135deg, rgba(166, 187, 124, 0.1), rgba(166, 187, 124, 0.05));
        }

        
        .scale-segment.red {
            background: linear-gradient(90deg, 
                rgba(196, 92, 90, 0.15), 
                rgba(196, 92, 90, 0.25),
                rgba(196, 92, 90, 0.35));
        }

        .scale-segment.yellow {
            background: linear-gradient(90deg, 
                rgba(236, 195, 60, 0.15), 
                rgba(236, 195, 60, 0.25),
                rgba(236, 195, 60, 0.35));
        }

        .scale-segment.green {
            background: linear-gradient(90deg, 
                rgba(166, 187, 124, 0.15), 
                rgba(166, 187, 124, 0.25),
                rgba(166, 187, 124, 0.35));
        }

       
        .red-level .level-indicator {
            background: #c45c5a;
        }

        .yellow-level .level-indicator {
            background: #ecc33c;
        }

        .green-level .level-indicator {
            background: #a6bb7c;
        }

        
        .red-level .indicator-icon,
        .yellow-level .indicator-icon,
        .green-level .indicator-icon {
            color: #ffffff;
        }

        
        .red-level .current-level {
            background: rgba(196, 92, 90, 0.1);
            border: 1px solid #c45c5a;
            color: #c45c5a;
        }

        .yellow-level .current-level {
            background: rgba(236, 195, 60, 0.1);
            border: 1px solid #ecc33c;
            color: #ecc33c;
        }

        .green-level .current-level {
            background: rgba(166, 187, 124, 0.1);
            border: 1px solid #a6bb7c;
            color: #a6bb7c;
        }
        
        .level-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            flex-wrap: wrap;
            gap: 15px;
            position: relative;
            z-index: 2;
        }
        
        .level-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .level-icon {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--level-bg);
            border-radius: 10px;
            font-size: 1.4rem;
            color: var(--level-color);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            aspect-ratio: 1 / 1;
            flex-shrink: 0;
        }
        
        .current-level {
            font-size: 1.1rem;
            font-weight: 600;
            padding: 10px 25px;
            background: var(--level-bg);
            border: 1px solid var(--level-color);
            color: var(--level-color);
            border-radius: 50px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .current-level i {
            font-size: 1.2rem;
        }
        
        
        .level-scale-container {
            position: relative;
            height: 90px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 45px;
            overflow: hidden;
            margin-bottom: 30px;
            border: 1px solid var(--border-color);
            display: flex;
            align-items: center;
        }
        
        .light-theme .level-scale-container {
            background: rgba(0, 0, 0, 0.05);
        }
        
        .level-scale-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            z-index: 1;
        }
        
        .scale-segment {
            flex: 1;
            height: 100%;
            position: relative;
        }
        
        
        .level-indicator {
            position: absolute;
            top: 50%;
            transform: translate(-50%, -50%);
            width: 70px;
            height: 70px;
            background: var(--level-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 3;
            box-shadow: none;
        }
        
        .indicator-icon {
            font-size: 1.8rem;
            color: white;
            text-shadow: none;
            z-index: 2;
            position: relative;
        }
        
        
        .level-description {
            background: var(--level-bg);
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            position: relative;
            z-index: 2;
        }
        
        .light-theme .level-description {
            border: 1px solid rgba(0, 0, 0, 0.1);
        }
        
        .level-description-title {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 15px;
            color: var(--level-color);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .level-description-title i {
            font-size: 1.2rem;
        }
        
        .level-description-text {
            font-size: 1.05rem;
            line-height: 1.6;
            color: var(--text-secondary);
        }
        
        .level-description-text strong {
            color: var(--level-color);
            font-weight: 700;
        }
        
        
        .level-info-button {
            display: flex;
            justify-content: center;
            margin-top: 20px;
            position: relative;
            z-index: 3;
        }
        
        
        .level-info-button .button-link {
            justify-content: space-between;
            padding: 14px 20px;
        }
        
        
        .level-info-button .button-link span:first-child {
            margin-left: 0;
        }
        
        
        .comments-section {
            background-color: var(--bg-secondary);
            border-radius: var(--menu-border-radius);
            padding: 35px 30px;
            margin-bottom: 40px;
            border: 1px solid var(--border-color);
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.4);
            overflow: hidden;
            border-left: 5px solid var(--orange-color);
        }
        
        .light-theme .comments-section {
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.1);
        }
        
        .comments-section .section-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .comments-section .section-title .title-icon {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 92, 32, 0.1);
            border-radius: 10px;
            font-size: 1.4rem;
            color: var(--orange-color);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            aspect-ratio: 1 / 1;
            flex-shrink: 0;
        }
        
        
        .pinned-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(255, 92, 32, 0.2);
            color: var(--orange-color);
            padding: 4px 10px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 5px;
            z-index: 10;
            border: 1px solid rgba(255, 92, 32, 0.3);
        }
        
        .pinned-badge i {
            font-size: 0.7rem;
        }
        
        
        .comments-pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid var(--border-color);
        }
        
        .pagination-button {
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: 50%;
            color: var(--text-secondary);
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1.2rem;
        }
        
        .pagination-button:hover:not(:disabled) {
            background: rgba(255, 255, 255, 0.1);
            color: var(--text-primary);
            border-color: rgba(255, 255, 255, 0.2);
        }
        
        .pagination-button:disabled {
            opacity: 0.3;
            cursor: not-allowed;
        }
        
        .pagination-info {
            font-size: 0.9rem;
            color: var(--text-secondary);
            font-weight: 600;
        }
        
        
        .comment-post {
            display: block;
            width: 100%;
            padding: 2.5rem 2.5rem 2rem;
            background-color: #383838;
            border-radius: 8px;
            margin-bottom: 20px;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            cursor: pointer;
            text-decoration: none !important;
            color: inherit;
            transition-duration: .2s;
            text-align: left;
        }
        
        .comment-post:nth-child(even) {
            --tw-bg-opacity: 1;
            background-color: rgb(56 56 56 / var(--tw-bg-opacity));
        }
        
        .light-theme .comment-post {
            background-color: rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(0, 0, 0, 0.08);
        }
        
        .comment-post .link-body {
            display: flex;
            height: 100%;
            flex-direction: column;
            width: 100%;
        }
        
        .comment-post .content {
            display: flex;
            flex: 1 1 auto;
            flex-direction: column;
            width: 100%;
        }
        
        
        .comment-post .link-date {
            width: auto;
            max-width: max-content;
            background-color: rgba(0, 0, 0, 0.1);
            padding: 0.5rem 1rem;
            font-size: 15px;
            font-weight: 600;
            color: #2d8b2d;
            border-radius: 4px;
            margin-bottom: 12px;
            display: inline-block;
            text-align: left;
            border: 1px solid rgba(0, 0, 0, 0.1);
        }

        
        .light-theme .comment-post .link-date {
            background-color: rgba(0, 0, 0, 0.08);
            color: #216621;
            border: 1px solid rgba(0, 0, 0, 0.15);
            font-weight: 700;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .comment-post .link-date time {
            color: inherit;
        }
        
        
        .comment-post h2 {
            margin-top: 0;
            margin-bottom: 16px;
            font-size: 21px;
            font-weight: 600;
            color: var(--text-primary);
            line-height: 1.4;
            text-align: left;
        }
        
        
        .comment-post p {
            margin-top: 0;
            margin-bottom: 0;
            font-size: 1rem;
            font-weight: 500;
            line-height: 1.75rem;
            color: #a4a6ab;
            text-align: left;
            max-height: none;
            overflow: visible;
            display: block;
            width: 100%;
        }
        
        .light-theme .comment-post p {
            color: rgba(26, 26, 26, 0.75);
        }
        
        
        .comment-post p .read-more {
            color: white;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            margin-left: 4px;
            white-space: nowrap;
        }
        
        .light-theme .comment-post p .read-more {
            color: var(--text-primary);
        }
        
        
        .comment-post:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
        }
        
        .light-theme .comment-post:hover {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }
        
        
       .buttons-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 25px;
    margin-bottom: 40px;
}
        
        .button-card {
            background-color: var(--bg-secondary);
            border-radius: var(--menu-border-radius);
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
            border: 1px solid var(--border-color);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        
        .light-theme .button-card {
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }
        
        .button-icon-container {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 60px;
            height: 60px;
            background: var(--accent-gradient);
            color: white;
            border-radius: var(--menu-border-radius);
            font-size: 1.6rem;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(136, 136, 136, 0.3);
        }
        
        .button-title {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 12px;
            color: var(--text-primary);
        }
        
        .button-description {
            font-size: 0.98rem;
            color: var(--text-secondary);
            margin-bottom: 20px;
            line-height: 1.5;
            flex-grow: 1;
        }
        
        .button-link {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            background: rgba(255, 255, 255, 0.05);
            color: white;
            text-decoration: none;
            padding: 14px 20px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.95rem;
            border: 1px solid var(--border-color);
            margin-top: auto;
            cursor: pointer;
            position: relative;
            z-index: 2;
        }
        
        .light-theme .button-link {
            background: rgba(0, 0, 0, 0.05);
            color: var(--text-primary);
        }
        
        
        .button-link:active {
            background-color: rgba(255, 255, 255, 0.15) !important;
            transform: translateY(1px);
        }
        
        .light-theme .button-link:active {
            background-color: rgba(0, 0, 0, 0.15) !important;
        }
        
        .button-link:hover {
            background: rgba(255, 255, 255, 0.1);
        }
        
        .light-theme .button-link:hover {
            background: rgba(0, 0, 0, 0.1);
        }
        
        .link-icon {
            font-size: 1.1rem;
        }
        
        
        .info-section {
            background: rgba(255, 255, 255, 0.03);
            padding: 30px;
            border-radius: var(--menu-border-radius);
            margin-top: 40px;
            margin-bottom: 40px;
            border-left: 4px solid #888;
        }
        
        .light-theme .info-section {
            background: rgba(0, 0, 0, 0.03);
            border-left: 4px solid #666;
        }
        
        .info-title {
            font-size: 1.5rem;
            margin-bottom: 15px;
            color: var(--text-primary);
        }
        
        .info-text {
            font-size: 1rem;
            line-height: 1.6;
            color: var(--text-secondary);
        }
        
        .support-link {
            color: #aaa;
            text-decoration: underline;
            font-weight: 600;
        }
        
        .light-theme .support-link {
            color: #666;
        }
        
        .support-link:hover {
            color: var(--text-primary);
        }
        
        
        .additional-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(min(100%, 340px), 1fr));
            gap: 25px;
            margin-top: 0;
            margin-bottom: 0;
        }
        
        
        .animated-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 60px;
            height: 60px;
            background: var(--accent-orange);
            color: white;
            border-radius: var(--menu-border-radius);
            font-size: 1.6rem;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(153, 153, 153, 0.3);
        }
        
        
        .decorative-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
            opacity: 0.02;
            background: transparent;
        }
        
        .light-theme .decorative-bg {
            opacity: 0.03;
            background: transparent;
        }
        
        .menu-overlay {
            position: fixed;
            top: var(--header-height);
            left: 0;
            width: 100%;
            height: calc(100% - var(--header-height));
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            display: none;
            backdrop-filter: blur(2px);
        }
        
        .menu-overlay.active {
            display: block;
        }
        
        
        
        #comment-content-page .page-header div[style*="margin-top: 10px"] {
            color: var(--text-secondary) !important;
            display: flex !important;
            align-items: center !important;
            gap: 8px !important;
            font-size: 1rem !important;
            font-weight: 500 !important;
            margin-top: 10px !important;
        }

        .light-theme #comment-content-page .page-header div[style*="margin-top: 10px"] {
            color: rgba(26, 26, 26, 0.7) !important;
        }

        #comment-content-page .page-header div[style*="margin-top: 10px"] i {
            font-size: 1rem !important;
        }
        
        
        @media (min-width: 769px) {
            .nav-list {
                width: 400px;
                max-width: 400px;
            }
        }
        
        @media (max-width: 768px) {
            .nav-list {
                width: calc(100% - 40px);
                max-width: calc(100% - 40px);
                max-height: 80vh;
            }
            
            
            .logo {
                gap: 8px;
            }
            
            .logo-icon {
                font-size: 1.6rem;
            }
            
            .logo-text {
                font-size: 1.6rem;
            }
            
            .channel-description {
                padding: 30px;
            }
            
            .load-level-section {
                padding: 25px 20px;
            }
            
            .comments-section {
                padding: 25px 20px;
            }
            
            .level-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            
            .current-level {
                align-self: flex-start;
            }
            
            .level-title {
                font-size: 1.6rem;
            }
            
            .comments-section .section-title {
                font-size: 1.6rem;
            }
            
            .level-indicator {
                width: 60px;
                height: 60px;
            }
            
            .indicator-icon {
                font-size: 1.5rem;
            }
            
            .level-scale-container {
                height: 80px;
            }
            
            
            .comment-post {
                padding: 2rem;
            }
            
            .comment-post h2 {
                font-size: 19px;
                margin-bottom: 14px;
            }
            
            .comment-post p {
                font-size: 15px;
                line-height: 1.65rem;
            }
            
            .comment-post .link-date {
                font-size: 14px;
                margin-bottom: 10px;
            }
            
            
            .theme-toggle {
                right: 70px;
            }
            
            
            #comment-content-page .level-icon {
                width: 35px !important;
                height: 35px !important;
                font-size: 1.2rem !important;
                min-width: 35px;
                min-height: 35px;
            }
            
            .comments-section .section-title .title-icon {
                width: 35px !important;
                height: 35px !important;
                font-size: 1.2rem !important;
            }
            
            
            .comments-pagination {
                gap: 15px;
            }
            
            .pagination-button {
                width: 45px;
                height: 45px;
                font-size: 1.1rem;
            }
            
            .pagination-info {
                font-size: 0.85rem;
            }
        }
        
        @media (max-width: 550px) {
            .comment-post {
                padding: 1.5rem;
                text-align: left;
            }
            
            .comment-post h2 {
                font-size: 18px;
                margin-bottom: 12px;
            }
            
            .comment-post p {
                font-size: 14px;
                line-height: 1.6rem;
            }
            
            .comment-post .link-date {
                font-size: 13px;
                margin-bottom: 8px;
            }
        }
        
        @media (max-width: 480px) {
            .content {
                padding: 20px 0 30px 0;
            }
            
            .nav-list {
                max-height: 75vh;
                top: calc(var(--header-height) + 10px);
            }
            
            
            .logo {
                gap: 6px;
                left: 50%;
                transform: translateX(-50%) translateY(-50%);
            }
            
            .logo-icon {
                font-size: 1.4rem;
            }
            
            .logo-text {
                font-size: 1.4rem;
                letter-spacing: 0;
            }
            
            .nav-list::-webkit-scrollbar {
                width: 6px;
            }
            
            .container {
                padding: 0 15px;
            }
            
            .channel-description {
                padding: 20px;
                margin-bottom: 20px;
            }
            
            .channel-title {
                font-size: 1.3rem;
            }
            
            .channel-text {
                font-size: 1rem;
                margin-bottom: 15px;
            }
            
            .subtitle {
                padding: 15px;
                font-size: 1rem;
                margin-bottom: 30px;
            }
            
            .load-level-section {
                padding: 20px 15px;
                margin-bottom: 30px;
            }
            
            .comments-section {
                padding: 20px 15px;
                margin-bottom: 30px;
            }
            
            .level-title {
                font-size: 1.4rem;
            }
            
            .comments-section .section-title {
                font-size: 1.4rem;
            }
            
            .level-icon {
                width: 35px;
                height: 35px;
                font-size: 1.2rem;
            }
            
            .comments-section .section-title .title-icon {
                width: 35px;
                height: 35px;
                font-size: 1.2rem;
            }
            
            .current-level {
                font-size: 1rem;
                padding: 8px 20px;
            }
            
            .level-scale-container {
                height: 70px;
            }
            
            .level-indicator {
                width: 55px;
                height: 55px;
            }
            
            .indicator-icon {
                font-size: 1.4rem;
            }
            
            .level-description {
                padding: 20px;
            }
            
            .level-description-title {
                font-size: 1.2rem;
            }
            
            .button-link {
                padding: 12px 16px;
            }
            
            .button-card {
                padding: 18px;
            }
            
            .button-icon-container, .animated-icon {
                width: 55px;
                height: 55px;
                font-size: 1.4rem;
                margin-bottom: 15px;
            }
            
            .button-title {
                font-size: 1.2rem;
            }
            
            .info-section {
                padding: 20px;
                margin-top: 30px;
                margin-bottom: 30px;
            }
            
            .additional-grid {
                gap: 20px;
            }
            
            .menu-toggle {
                padding: 10px 20px;
                font-size: 0.9rem;
            }
            
            .nav-link {
                padding: 16px 20px;
                font-size: 0.95rem;
            }
            
            .dropdown-item {
                padding: 14px 40px;
            }
            
            .dropdown-item::before {
                left: 24px;
            }
            
            .telegram-icon {
                right: 15px;
                width: 40px;
                height: 40px;
            }
            
            .theme-toggle {
                right: 70px;
                width: 40px;
                height: 40px;
            }
            
            .telegram-icon i {
                font-size: 1.1rem;
            }
            
            .theme-toggle i {
                font-size: 1.1rem;
            }
            
            .nav-footer {
                padding: 15px;
            }
            
            .nav-footer-text {
                font-size: 0.85rem;
            }
            
            
            .comments-pagination {
                gap: 10px;
                margin-top: 20px;
                padding-top: 15px;
            }
            
            .pagination-button {
                width: 40px;
                height: 40px;
                font-size: 1rem;
            }
            
            .pagination-info {
                font-size: 0.8rem;
            }
            
           
            .pinned-badge {
                top: 10px;
                right: 10px;
                font-size: 0.7rem;
                padding: 3px 8px;
            }
        }
        
        @media (max-width: 380px) {
            .comment-post {
                padding: 1.25rem;
            }
            
            .comment-post h2 {
                font-size: 17px;
            }
            
            .comment-post p {
                font-size: 13px;
                line-height: 1.5rem;
            }
            
            .comment-post .link-date {
                font-size: 12.5px;
            }
            
            .telegram-icon {
                right: 12px;
                width: 38px;
                height: 38px;
            }
            
            .theme-toggle {
                right: 67px;
                width: 38px;
                height: 38px;
            }
            
            .telegram-icon i {
                font-size: 1rem;
            }
            
            .theme-toggle i {
                font-size: 1rem;
            }
            
            .menu-toggle {
                padding: 8px 16px;
                font-size: 0.85rem;
            }
            
            .load-level-section {
                padding: 15px;
            }
            
            .comments-section {
                padding: 15px;
            }
            
            .level-scale-container {
                height: 65px;
            }
            
            .level-indicator {
                width: 50px;
                height: 50px;
            }
            
            .indicator-icon {
                font-size: 1.3rem;
            }
        }
        
        @media (max-width: 350px) {
            .logo {
                gap: 4px;
            }
            
            .logo-icon {
                font-size: 1.2rem;
            }
            
            .logo-text {
                font-size: 1.2rem;
            }
            
            .telegram-icon {
                right: 10px;
                width: 36px;
                height: 36px;
            }
            
            .theme-toggle {
                right: 65px;
                width: 36px;
                height: 36px;
            }
            
            .menu-toggle {
                padding: 8px 14px;
                font-size: 0.8rem;
            }
            
            .container {
                padding: 0 12px;
            }
            
            .comment-post {
                padding: 1rem 0.75rem 0.75rem;
            }
            
            .comment-post h2 {
                font-size: 16px;
                margin-bottom: 0.75rem;
            }
            
            .comment-post p {
                font-size: 12px;
                line-height: 1.4rem;
            }
            
            .comment-post .link-date {
                font-size: 12px;
            }
        }
        
        @media (max-height: 500px) {
            .nav-list {
                max-height: 65vh;
            }
        }
        
        
        @media (hover: hover) {
            .menu-toggle:hover {
                background-color: rgba(255, 255, 255, 0.1);
                border-color: rgba(255, 255, 255, 0.2);
            }
            
            .light-theme .menu-toggle:hover {
                background-color: rgba(0, 0, 0, 0.15);
                border-color: rgba(0, 0, 0, 0.25);
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
            }
            
            .nav-link:hover, 
            .nav-link.active,
            .dropdown.active .dropdown-toggle {
                background-color: rgba(255, 255, 255, 0.08);
                color: var(--text-primary);
            }
            
            .light-theme .nav-link:hover,
            .light-theme .nav-link.active,
            .light-theme .dropdown.active .dropdown-toggle {
                background-color: rgba(0, 0, 0, 0.08);
            }
            
            .dropdown-item:hover {
                background-color: rgba(255, 255, 255, 0.08);
                color: var(--text-primary);
            }
            
            .light-theme .dropdown-item:hover {
                background-color: rgba(0, 0, 0, 0.08);
            }
            
            .support-link:hover {
                color: var(--text-primary);
            }
            
            .telegram-icon:hover {
                background: rgba(255, 255, 255, 0.1);
                color: var(--text-primary);
                border-color: rgba(255, 255, 255, 0.2);
            }
            
            .light-theme .telegram-icon:hover {
                background: rgba(0, 0, 0, 0.1);
                border-color: rgba(0, 0, 0, 0.2);
            }
            
            .theme-toggle:hover {
                background: rgba(255, 255, 255, 0.1);
                color: var(--text-primary);
                border-color: rgba(255, 255, 255, 0.2);
            }
            
            .light-theme .theme-toggle:hover {
                background: rgba(0, 0, 0, 0.15);
                border-color: rgba(0, 0, 0, 0.25);
            }
            
            .comment-post:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
            }
            
            .light-theme .comment-post:hover {
                box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            }
        }
        
        
        ::selection {
            background-color: rgba(136, 136, 136, 0.4);
            color: #ffffff;
        }

        
        ::-moz-selection {
            background-color: rgba(136, 136, 136, 0.4);
            color: #ffffff;
        }

       
        ::-webkit-selection {
            background-color: rgba(136, 136, 136, 0.4);
            color: #ffffff;
        }
        
        .light-theme ::selection {
            background-color: rgba(136, 136, 136, 0.3);
            color: #1a1a1a;
        }
        
        .light-theme ::-moz-selection {
            background-color: rgba(136, 136, 136, 0.3);
            color: #1a1a1a;
        }
        
        .light-theme ::-webkit-selection {
            background-color: rgba(136, 136, 136, 0.3);
            color: #1a1a1a;
        }
        
        
        .empty-comments {
            text-align: center;
            padding: 40px;
            color: rgba(255, 255, 255, 0.5);
            font-size: 1.1rem;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 10px;
            border: 1px dashed rgba(255, 255, 255, 0.1);
        }

        .light-theme .empty-comments {
            color: rgba(26, 26, 26, 0.5);
            background: rgba(0, 0, 0, 0.03);
            border: 1px dashed rgba(0, 0, 0, 0.1);
        }

        .error-loading {
            text-align: center;
            padding: 30px;
            color: #c45c5a;
            background: rgba(196, 92, 90, 0.1);
            border-radius: 10px;
            border: 1px solid rgba(196, 92, 90, 0.3);
        }

        .light-theme .error-loading {
            color: #c45c5a;
            background: rgba(196, 92, 90, 0.1);
            border: 1px solid rgba(196, 92, 90, 0.3);
        }
        
        
.level-description-text a {
    color: #aaa;
    text-decoration: underline;
    font-weight: 600;
}

.level-description-text a:hover {
    color: var(--text-primary);
}

.light-theme .level-description-text a {
    color: #666;
}

.light-theme .level-description-text a:hover {
    color: var(--text-primary);
}

.level-description-text p {
    margin-bottom: 1rem;
}

.level-description-text ul,
.level-description-text ol {
    margin-bottom: 1rem;
    padding-left: 1.5rem;
}

.level-description-text li {
    margin-bottom: 0.5rem;
}

.level-description-text strong {
    font-weight: 700;
    color: var(--text-primary);
}

.level-description-text em {
    font-style: italic;
}

.level-description-text h3,
.level-description-text h4 {
    margin: 1.5rem 0 1rem 0;
    color: var(--text-primary);
    font-weight: 600;
}

.level-description-text h3 {
    font-size: 1.3rem;
}

.level-description-text h4 {
    font-size: 1.1rem;
}


.level-description-text a.content-link {
    color: var(--orange-color);
    text-decoration: none;
    border-bottom: 1px solid transparent;
    transition: all 0.2s ease;
}

.level-description-text a.content-link:hover {
    border-bottom: 1px solid var(--orange-color);
}


.level-description-text pre,
.level-description-text code {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 4px;
    padding: 2px 6px;
    font-family: 'Courier New', monospace;
    font-size: 0.9rem;
}

.level-description-text pre {
    padding: 1rem;
    overflow-x: auto;
    margin: 1rem 0;
}

.light-theme .level-description-text pre,
.light-theme .level-description-text code {
    background: rgba(0, 0, 0, 0.05);
}


.requirements-list {
    list-style-type: none;
    padding-left: 0;
    margin: 1rem 0;
}

.requirement-item {
    padding-left: 1.5rem;
    position: relative;
    margin-bottom: 0.5rem;
}

.requirement-item:before {
    content: '•';
    position: absolute;
    left: 0;
    color: var(--orange-color);
    font-weight: bold;
}

@media (min-width: 1400px) {
    .container {
        padding: 0 40px;
    }
    
    .buttons-grid {
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    }
    
    .additional-grid {
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    }
}

@media (min-width: 1920px) {
    .container {
        padding: 0 60px;
    }
    
    .buttons-grid {
        grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
    }
    
    .additional-grid {
        grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
    }
}


.channel-description,
.load-level-section,
.comments-section,
.info-section {
    width: 100%;
    max-width: 100%;
}


@media (min-width: 1600px) {
    .buttons-grid {
        grid-template-columns: repeat(auto-fill, minmax(370px, 1fr));
        gap: 30px;
    }
}


.content {
    width: 100%;
    max-width: 100%;
}


.comment-link {
    text-decoration: none;
    color: inherit;
    display: block;
}

.comment-link:hover {
    text-decoration: none;
}


.footer {
    background-color: var(--bg-secondary);
    border-top: 1px solid var(--border-color);
    padding: 30px 0 20px;
    margin-top: auto;
    flex-shrink: 0;
}

.footer-content {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 40px;
    margin-bottom: 40px;
}

.footer-section {
    display: flex;
    flex-direction: column;
}

.footer-logo {
    display: flex;
    align-items: center;
    gap: 10px;
    color: var(--text-primary);
    text-decoration: none;
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 15px;
}

.footer-logo i {
    font-size: 1.8rem;
}

.footer-logo-text {
    background: var(--accent-gradient);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
}

.footer-description {
    color: var(--text-secondary);
    font-size: 0.95rem;
    line-height: 1.5;
    margin-bottom: 20px;
}

.footer-social {
    display: flex;
    gap: 15px;
    margin-top: 10px;
}

.social-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    text-decoration: none;
    transition: all 0.3s ease;
    font-size: 1.1rem;
}

.social-icon.telegram {
    background: linear-gradient(135deg, #0088cc, #005c8a);
}

.social-icon.chat {
    background: linear-gradient(135deg, #25D366, #128C7E);
}

.social-icon.bot {
    background: linear-gradient(135deg, #888, #666);
}

.social-icon:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
}

.footer-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 20px;
    position: relative;
    padding-bottom: 10px;
}

.footer-title::after {
    content: '';
    position: absolute;
    left: 0;
    bottom: 0;
    width: 40px;
    height: 3px;
    background: var(--accent-gradient);
    border-radius: 2px;
}

.footer-links {
    list-style: none;
    padding: 0;
    margin: 0;
}

.footer-links li {
    margin-bottom: 12px;
}

.footer-links a {
    color: var(--text-secondary);
    text-decoration: none;
    font-size: 0.95rem;
    transition: all 0.2s ease;
    display: inline-block;
}

.footer-links a:hover {
    color: var(--text-primary);
    transform: translateX(5px);
}

.footer-bottom {
    text-align: center;
    padding-top: 30px;
    border-top: 1px solid var(--border-color);
}

.copyright {
    color: var(--text-secondary);
    font-size: 0.9rem;
    margin-bottom: 10px;
}

.footer-note {
    color: var(--text-secondary);
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.footer-note i {
    color: #ff5c20;
    font-size: 0.8rem;
}


@media (max-width: 768px) {
    .footer {
        padding: 15px 0 20px;
    }
    
    .footer-content {
        gap: 30px;
        margin-bottom: 30px;
    }
    
    .footer-social {
        justify-content: flex-start;
    }
}

@media (max-width: 480px) {
    .footer-content {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .footer-section {
        text-align: left;
    }
    
    .footer-logo {
        justify-content: flex-start;
    }
    
    .footer-title::after {
        left: 0;
        transform: none;
    }
    
    .footer-social {
        justify-content: flex-start;
    }
    
    .footer-links a:hover {
        transform: translateX(5px);
    }
}


.articles-section {
    margin-bottom: 40px;
    width: 100%;
}

.articles-section .container {
    padding: 0;
    width: 100%;
}

.articles-section .full-width-card {
    background-color: var(--bg-secondary);
    border-radius: var(--menu-border-radius);
    padding: 35px 30px;
    border: 1px solid var(--border-color);
    box-shadow: 0 5px 25px rgba(0, 0, 0, 0.4);
    text-align: center;
    width: 100%;
    max-width: 100%;
}

.light-theme .articles-section .full-width-card {
    box-shadow: 0 5px 25px rgba(0, 0, 0, 0.1);
}

.articles-section .button-icon-container {
    background: linear-gradient(135deg, #6a89cc, #4a69bd);
    margin: 0 auto 20px;
}

.articles-section .button-title {
    font-size: 1.8rem;
    margin-bottom: 15px;
    text-align: center;
}

.articles-section .button-description {
    font-size: 1.1rem;
    max-width: 800px;
    margin: 0 auto 25px;
    text-align: center;
}

.articles-section .button-link {
    max-width: 250px;
    margin: 0 auto;
    background: linear-gradient(135deg, #6a89cc, #4a69bd);
    border: none;
    color: white;
}

.articles-section .button-link:hover {
    background: linear-gradient(135deg, #5a79bc, #3a59ad);
}


@media (max-width: 768px) {
    .articles-section .full-width-card {
        padding: 25px 20px;
    }
    
    .articles-section .button-title {
        font-size: 1.6rem;
    }
    
    .articles-section .button-description {
        font-size: 1rem;
    }
}

@media (max-width: 480px) {
    .articles-section .full-width-card {
        padding: 20px 15px;
    }
    
    .articles-section .button-title {
        font-size: 1.4rem;
    }
    
    .articles-section .button-description {
        font-size: 0.95rem;
    }
    
    .articles-section .button-icon-container {
        width: 50px;
        height: 50px;
        font-size: 1.4rem;
    }
}
 .load-level-section.loading {
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.2s ease;
}
      

::-webkit-scrollbar {
    width: 10px;
    height: 10px;
}

::-webkit-scrollbar-track {
    background: var(--bg-secondary);
}

::-webkit-scrollbar-thumb {
    background: var(--scrollbar-thumb);
    border-radius: 5px;
}

::-webkit-scrollbar-thumb:hover {
    background: var(--scrollbar-thumb-hover);
}


* {
    scrollbar-width: thin;
    scrollbar-color: var(--scrollbar-thumb) var(--bg-secondary);
}
        
        
    </style>
</head>
<body>
    
    <div class="decorative-bg"></div>
    
    
    <div class="menu-overlay" id="menu-overlay"></div>
    
    
    <header class="header" id="header">
        <div class="container">
            <nav class="main-nav">
                
                <button class="theme-toggle" id="theme-toggle" title="Переключить тему">
                    <i class="fas fa-moon"></i>
                    <i class="fas fa-sun"></i>
                </button>
                
                
                <a href="https://t.me/ishupodrygyilidryga" class="telegram-icon" target="_blank" rel="noopener noreferrer" title="Перейти в Telegram канал">
                    <i class="fab fa-telegram"></i>
                </a>
                
                
                <a href="https://ishupodrygyilidryga.fun" class="logo" id="logo" title="Перейти на главную страницу">
                    <i class="fas fa-users logo-icon"></i>
                    <span class="logo-text">iipd</span>
                </a>
                
                
                <button class="menu-toggle" id="menu-toggle">
                    <i class="fas fa-bars"></i>
                    <span>Меню</span>
                </button>
                
                
                <ul class="nav-list" id="nav-list">
                    <li class="nav-item">
                        <a href="https://ishupodrygyilidryga.fun" class="nav-link">
                            <span class="link-text">
                                <i class="fas fa-home"></i> Главная
                            </span>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="https://t.me/ishupodrygyilidryga" class="nav-link" target="_blank" rel="noopener noreferrer">
                            <span class="link-text">
                                <i class="fab fa-telegram"></i> Наш канал
                            </span>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="https://t.me/ankety_bot" class="nav-link" target="_blank" rel="noopener noreferrer">
                            <span class="link-text">
                                <i class="fas fa-robot"></i> Бот для анкет
                            </span>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="https://t.me/lymikanon" class="nav-link" target="_blank" rel="noopener noreferrer">
                            <span class="link-text">
                                <i class="fas fa-comments"></i> Наш чат
                            </span>
                        </a>
                    </li>
                    
                    
                    <li class="nav-item dropdown">
                        <button class="nav-link dropdown-toggle">
                            <span class="link-text">
                                <i class="fas fa-chart-line"></i> Tgstat
                            </span>
                        </button>
                        <div class="dropdown-menu">
                            <a href="https://uk.tgstat.com/channel/@ishupodrygyilidryga" class="dropdown-item" target="_blank" rel="noopener noreferrer">
                                <span class="dropdown-item-text">iipd</span>
                            </a>
                            <a href="https://uk.tgstat.com/channel/@surrealtouch" class="dropdown-item" target="_blank" rel="noopener noreferrer">
                                <span class="dropdown-item-text">surrealtouch</span>
                            </a>
                        </div>
                    </li>
                    
                    
                    <li class="nav-item dropdown">
                        <button class="nav-link dropdown-toggle">
                            <span class="link-text">
                                <i class="fas fa-user-shield"></i> Стать Админом
                            </span>
                        </button>
                        <div class="dropdown-menu">
                            <a href="https://t.me/info_ishupodrygyilidryga/27" class="dropdown-item" target="_blank" rel="noopener noreferrer">
                                <span class="dropdown-item-text">По Анкетам</span>
                            </a>
                            <a href="https://t.me/info_ishupodrygyilidryga/897" class="dropdown-item" target="_blank" rel="noopener noreferrer">
                                <span class="dropdown-item-text">По Аватаркам</span>
                            </a>
                        </div>
                    </li>
                    
                    
                    <li class="nav-footer">
                        <p class="nav-footer-text">
                            Ищу интернет подругу/друга (WEB)</p>
                            <a href="https://ishupodrygyilidryga.fun" class="nav-footer-link" target="_blank" rel="noopener noreferrer">ishupodrygyilidryga.fun</a>
                        </p>
                        <p class="nav-footer-year">2026</p>
                    </li>
                </ul>
            </nav>
        </div>
    </header>
    
   
    <main class="content">
        <div class="container">
            <div class="channel-description">
                <h2 class="channel-title">Ищу интернет подругу/друга</h2>
                <p class="channel-text">Это канал, созданный для людей, которые хотят найти новых друзей и общаться с интересными людьми в интернете.</p>
                <p class="channel-text">Здесь вы сможете найти единомышленников, обсудить разные темы, поделиться своими мыслями и эмоциями, а также получить поддержку и советы от других участников.</p>
                <p class="channel-text">Мы призываем к уважению и дружелюбному общению, чтобы каждый мог почувствовать себя комфортно и понимаемым.</p>
                <p class="channel-text">Присоединяйтесь к нам и найдите своего идеального интернет друга или подругу!</p>
            </div>
            
            
            <section class="load-level-section loading" id="load-level">
                <div class="level-header">
                    <h2 class="level-title">
                        <span class="level-icon">
                            <i class="fas fa-chart-line"></i>
                        </span>
                        Шкала загруженности анкет
                    </h2>
                    <div class="current-level">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span id="current-level-text">Красный уровень</span>
                    </div>
                </div>
                
                <div class="level-scale-container">
                    <div class="level-scale-bg">
                        <div class="scale-segment red"></div>
                        <div class="scale-segment yellow"></div>
                        <div class="scale-segment green"></div>
                    </div>
                    
                    <div class="level-indicator" id="level-indicator">
                        <div class="indicator-icon">
                            <i class="fas fa-exclamation"></i>
                        </div>
                    </div>
                </div>
                
                <div class="level-description">
                    <h3 class="level-description-title">
                        <i class="fas fa-clock"></i>
                        <span id="level-description-title">Красный уровень загруженности</span>
                    </h3>
                    <p class="level-description-text" id="level-description-text">
                        <strong>Ожидание публикации анкеты - больше одного день.</strong> 
                        В данный момент у нас очень высокий поток анкет. Из-за большого количества заявок обработка занимает больше времени. Пожалуйста, проявите терпение - ваша анкета будет обязательно обработана!
                    </p>
                </div>
                
                <div class="level-info-button">
                    <a href="https://ishupodrygyilidryga.fun/status" class="button-link">
                        <span>Что такое уровни загруженности?</span>
                        <span class="link-icon">→</span>
                    </a>
                </div>
            </section>
            
            
            <section class="comments-section" id="comments">
                <h2 class="section-title">
                    <span class="title-icon">
                        <i class="fas fa-comment-dots"></i>
                    </span>
                    Комментарии
                </h2>
                
                <div id="comments-container">
                    
                </div>
            </section>
            
            
            <div class="buttons-grid" id="main-blocks-container">
                
            </div>
      
            <div class="info-section">
                <h2 class="info-title">Важная информация</h2>
                <p class="info-text">Все ссылки ведут на официальные страницы с актуальной информацией. Если у вас возникли проблемы с доступом или вы обнаружили неработающую ссылку, пожалуйста, сообщите об этом в <a href="https://t.me/helpforsilencebot" class="support-link" target="_blank" rel="noopener noreferrer">поддержку</a>.</p>
            </div>
            
            
            <div class="additional-grid" id="additional-blocks-container">
                
            </div>
        </div>
    </main>

            
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                
                <div class="footer-section">
                    <a href="https://ishupodrygyilidryga.fun" class="footer-logo">
                        <i class="fas fa-users"></i>
                        <span class="footer-logo-text">iipd</span>
                    </a>
                    <p class="footer-description">
                        Канал для поиска новых друзей и интересного общения в интернете
                    </p>
                    <div class="footer-social">
                        <a href="https://t.me/ishupodrygyilidryga" class="social-icon telegram" target="_blank" rel="noopener noreferrer" title="Telegram канал">
                            <i class="fab fa-telegram"></i>
                        </a>
                        <a href="https://t.me/lymikanon" class="social-icon chat" target="_blank" rel="noopener noreferrer" title="Наш чат">
                            <i class="fas fa-comments"></i>
                        </a>
                        <a href="https://t.me/ankety_bot" class="social-icon bot" target="_blank" rel="noopener noreferrer" title="Бот для анкет">
                            <i class="fas fa-robot"></i>
                        </a>
                    </div>
                </div>
                
                <div class="mc-article-hero-attribution-author">
                    <h3 class="footer-title">Автор: Администрация iipd</h3>
    <img id="author-avatar" src="https://www.minecraft.net/content/dam/minecraftnet/franchise/author-avatars/Mojavatar_Staff-Villager_200x296.png" alt="Avatar">
    <dl>
</div>
                
                <div class="footer-section">
                    <h3 class="footer-title">Быстрые ссылки</h3>
                    <ul class="footer-links">
                        <li><a href="https://ishupodrygyilidryga.fun">Главная</a></li>
                        <li><a href="https://t.me/ishupodrygyilidryga" target="_blank" rel="noopener noreferrer">Канал</a></li>
                        <li><a href="https://t.me/ankety_bot" target="_blank" rel="noopener noreferrer">Бот анкет</a></li>
                        <li><a href="https://t.me/lymikanon" target="_blank" rel="noopener noreferrer">Чат</a></li>
                        <li><a href="https://t.me/helpforsilencebot" target="_blank" rel="noopener noreferrer">Поддержка</a></li>
                    </ul>
                </div>
                
                
                <div class="footer-section">
                    <h3 class="footer-title">Информация</h3>
                    <ul class="footer-links">
                        <li><a href="https://ishupodrygyilidryga.fun/status">Статус загруженности</a></li>
                        <li><a href="https://t.me/info_ishupodrygyilidryga/27" target="_blank" rel="noopener noreferrer">Стать админом по Анкетам</a></li>
                        <li><a href="https://t.me/info_ishupodrygyilidryga/897" target="_blank" rel="noopener noreferrer">Стать админом по Аватаркам</a></li>
                        <li><a href="https://uk.tgstat.com/channel/@ishupodrygyilidryga" target="_blank" rel="noopener noreferrer">Tgstat iipd</a></li>
                        <li><a href="https://uk.tgstat.com/channel/@surrealtouch" target="_blank" rel="noopener noreferrer">Tgstat surrealtouch</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p class="copyright">
                    &copy; 2026 Ищу интернет подругу/друга (WEB)
    </footer>

    <script>
        
        (function() {
            'use strict';
            
            
            const dom = {
                menuToggle: document.getElementById('menu-toggle'),
                navList: document.getElementById('nav-list'),
                menuOverlay: document.getElementById('menu-overlay'),
                body: document.body,
                dropdowns: document.querySelectorAll('.dropdown'),
                dropdownToggles: document.querySelectorAll('.dropdown-toggle'),
                themeToggle: document.getElementById('theme-toggle'),
                
                loadLevelSection: document.getElementById('load-level'),
                levelIndicator: document.getElementById('level-indicator'),
                currentLevelText: document.getElementById('current-level-text'),
                levelDescriptionTitle: document.getElementById('level-description-title'),
                levelDescriptionText: document.getElementById('level-description-text')
            };
            
            
            let isMenuOpen = false;
            let isLightTheme = false;
            
            
            let currentCommentPage = 0;
            const COMMENTS_PER_PAGE = 3;
            
            
            function applyTheme(theme) {
                if (theme === 'light') {
                    dom.body.classList.add('light-theme');
                    isLightTheme = true;
                } else {
                    dom.body.classList.remove('light-theme');
                    isLightTheme = false;
                }
            }
            
            
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme === 'light') {
                isLightTheme = true;
                dom.body.classList.add('light-theme');
            } else {
                isLightTheme = false;
                dom.body.classList.remove('light-theme');
                
                if (!savedTheme) {
                    localStorage.setItem('theme', 'dark');
                }
            }
            
            
            window.addEventListener('storage', function(e) {
                if (e.key === 'theme') {
                    applyTheme(e.newValue);
                }
            });
            
           
            const loadLevels = {
                red: {
                    title: "Красный уровень загруженности",
                    levelText: "Красный уровень",
                    description: "Ожидание публикации анкеты - больше одного дня.",
                    details: "В данный момент у нас очень высокий поток анкет. Из-за большого количества заявок обработка занимает больше времени. Пожалуйста, проявите терпение - ваша анкета будет обязательно обработана!",
                    indicatorPosition: 16.6,
                    icon: "fa-exclamation",
                    currentLevelIcon: "fa-exclamation-triangle"
                },
                yellow: {
                    title: "Жёлтый уровень загруженности",
                    levelText: "Жёлтый уровень",
                    description: "Ожидание публикации анкеты - больше одного дня / в течении дня.",
                    details: "Загруженность анкет средняя. Ваша анкета будет обработана в течение дня. Обращаем внимание, что время обработки может варьироваться в зависимости от времени суток.",
                    indicatorPosition: 50,
                    icon: "fa-exclamation-circle",
                    currentLevelIcon: "fa-exclamation-circle"
                },
                green: {
                    title: "Зелёный уровень загруженности",
                    levelText: "Зелёный уровень",
                    description: "Ожидание публикации анкеты - в день создания.",
                    details: "Низкая загруженность анкет. Ваша анкета будет обработана в день создания. Это оптимальное время для отправки анкеты!",
                    indicatorPosition: 83.4,
                    icon: "fa-check-circle",
                    currentLevelIcon: "fa-check-circle"
                }
            };
            
           
            let currentLoadLevel = "yellow";
            
         
            function setLoadLevel(level) {
                if (!loadLevels[level]) return;
                
                currentLoadLevel = level;
                const levelData = loadLevels[level];
                
             
                dom.loadLevelSection.className = "load-level-section " + level + "-level";
                
              
                dom.levelIndicator.style.left = levelData.indicatorPosition + "%";
                
              
                dom.currentLevelText.textContent = levelData.levelText;
                
         
                dom.levelDescriptionTitle.textContent = levelData.title;
                dom.levelDescriptionText.innerHTML = `<strong>${levelData.description}</strong> ${levelData.details}`;
                
           
                const indicatorIcon = dom.levelIndicator.querySelector('.indicator-icon i');
                indicatorIcon.className = "fas " + levelData.icon;
                
             
                const currentLevelIcon = document.querySelector('.current-level i');
                currentLevelIcon.className = "fas " + levelData.currentLevelIcon;
            }

     
function loadComments(page = 0) {
    fetch('comments-data.json?trim=true&v=' + Date.now())
        .then(response => {
            if (!response.ok) {
                throw new Error('Ошибка сети');
            }
            return response.json();
        })
        .then(comments => {
            const container = document.getElementById('comments-container');
            
        
            window.allComments = comments;
            
            if (comments.length === 0) {
                container.innerHTML = '<div class="empty-comments">Комментариев пока нет</div>';
                updatePaginationControls(comments.length);
                return;
            }
            
     
            comments.sort((a, b) => {
          
                const aPinned = a.pinned ? 1 : 0;
                const bPinned = b.pinned ? 1 : 0;
                
                if (aPinned !== bPinned) {
                    return bPinned - aPinned;
                }
                
        
                return b.timestamp - a.timestamp;
            });
            
     
            window.allCommentsSorted = comments;
            
    
            const startIndex = page * COMMENTS_PER_PAGE;
            const endIndex = startIndex + COMMENTS_PER_PAGE;
            const commentsToShow = comments.slice(startIndex, endIndex);
            
            container.innerHTML = '';
            
            if (commentsToShow.length === 0) {
                container.innerHTML = '<div class="empty-comments">Комментариев на этой странице нет</div>';
                return;
            }
            
            commentsToShow.forEach(comment => {
           
                let shortText = comment.short_text || '';
                
            
                if (!shortText || shortText.trim() === '') {
                    const simpleText = comment.text_simple || '';
                    shortText = simpleText + ' <span class="read-more">читать далее...</span>';
                } else if (!shortText.includes('<span class="read-more">')) {
               
                    shortText = shortText.replace('читать далее...', '<span class="read-more">читать далее...</span>');
                }
                
           
                const commentHTML = `
                    <a href="comment.php?slug=${encodeURIComponent(comment.slug || comment.id)}" class="comment-link">
                        <div class="comment-post" data-comment-id="${comment.id}">
                            ${comment.pinned ? 
                                '<div class="pinned-badge"><i class="fas fa-thumbtack"></i>Закреплено</div>' : 
                                ''}
                            <div class="link-body">
                                <div class="content">
                                    <div class="link-date">
                                        <time datetime="${comment.date}">${comment.date}</time>
                                    </div>
                                    <h2>${comment.title}</h2>
                                    <p>${shortText || ''}</p>
                                </div>
                            </div>
                        </div>
                    </a>
                `;
                container.innerHTML += commentHTML;
            });
            
     
            updatePaginationControls(comments.length, page);
        })
        .catch(error => {
            console.error('Ошибка загрузки комментариев:', error);
            const container = document.getElementById('comments-container');
            container.innerHTML = '<div class="error-loading">Не удалось загрузить комментарии</div>';
        });
}

          
            function updatePaginationControls(totalComments, currentPage = 0) {
                const totalPages = Math.ceil(totalComments / COMMENTS_PER_PAGE);
                
            
                let paginationContainer = document.querySelector('.comments-pagination');
                
                if (!paginationContainer) {
                    paginationContainer = document.createElement('div');
                    paginationContainer.className = 'comments-pagination';
                    document.getElementById('comments').appendChild(paginationContainer);
                }
                
            
                if (totalPages <= 1) {
                    paginationContainer.style.display = 'none';
                    return;
                }
                
                paginationContainer.style.display = 'flex';
                
                paginationContainer.innerHTML = `
                    <button class="pagination-button prev-button" ${currentPage === 0 ? 'disabled' : ''}>
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <span class="pagination-info">${currentPage + 1} из ${totalPages}</span>
                    <button class="pagination-button next-button" ${currentPage >= totalPages - 1 ? 'disabled' : ''}>
                        <i class="fas fa-chevron-right"></i>
                    </button>
                `;
                
          
                const prevButton = paginationContainer.querySelector('.prev-button');
                const nextButton = paginationContainer.querySelector('.next-button');
                
                if (prevButton) {
                    prevButton.addEventListener('click', () => {
                        if (currentPage > 0) {
                            currentCommentPage = currentPage - 1;
                            loadComments(currentCommentPage);
                            scrollToComments();
                        }
                    });
                }
                
                if (nextButton) {
                    nextButton.addEventListener('click', () => {
                        if (currentPage < totalPages - 1) {
                            currentCommentPage = currentPage + 1;
                            loadComments(currentCommentPage);
                            scrollToComments();
                        }
                    });
                }
            }

       
            function scrollToComments() {
                const commentsSection = document.getElementById('comments');
                if (commentsSection) {
              
                    const headerHeight = document.querySelector('.header')?.offsetHeight || 70;
                    const targetPosition = commentsSection.offsetTop - headerHeight - 20;
                    
                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                }
            }

          
            function loadCommentContentPage(comment) {
           
                if (comment.slug && comment.slug.trim() !== '') {
                    window.location.href = `comment.php?slug=${encodeURIComponent(comment.slug)}`;
                    return;
                }
                
             
            
                document.body.style.paddingTop = 'var(--header-height)';
                
            
                const currentContent = document.querySelector('.content .container');
                if (currentContent) {
                    currentContent.style.display = 'none';
                }
                
           
                const mainContent = document.querySelector('.content');
                if (!mainContent) return;
                
             
                let displayText = '';
                
           
                if (comment.text_formatted && comment.text_formatted.trim() !== '') {
                    displayText = comment.text_formatted;
                } 
          
                else if (comment.text && comment.text.trim() !== '') {
                    displayText = comment.text;
                }
         
                else if (comment.text_simple && comment.text_simple.trim() !== '') {
                    const simpleText = comment.text_simple;
                    const escapedText = simpleText
                        .replace(/&/g, '&amp;')
                        .replace(/</g, '&lt;')
                        .replace(/>/g, '&gt;')
                        .replace(/"/g, '&quot;')
                        .replace(/'/g, '&#039;');
                    displayText = escapedText.replace(/\n/g, '<br>');
                } 
         
                else {
                    displayText = '<p style="text-align:center;color:rgba(255,255,255,0.5);font-style:italic;padding:40px;">Нет содержимого</p>';
                }
                
              
                displayText = displayText.replace(/<div class="content-section">/g, '');
                displayText = displayText.replace(/<\/div>/g, '');
                
             
                const commentContentHTML = `
                    <div class="container">
                        <section class="load-level-section" style="border-left: 5px solid var(--orange-color);">
                            <div class="level-header">
                                <h2 class="level-title">
                                    <span class="level-icon" style="background: rgba(255, 92, 32, 0.1); color: var(--orange-color);">
                                        <i class="fas fa-comment-dots"></i>
                                    </span>
                                    ${comment.title || 'Без названия'}
                                </h2>
                                <div class="current-level" style="background: rgba(255, 92, 32, 0.1); border-color: var(--orange-color); color: var(--orange-color);">
                                    <i class="fas fa-calendar"></i>
                                    <span>${comment.date || 'Дата не указана'}</span>
                                </div>
                            </div>
                            
                            <div class="level-description" style="background: rgba(255, 255, 255, 0.03); border: 1px solid var(--border-color);">
                                <div class="level-description-text" style="font-size: 1.1rem; line-height: 1.7;">
                                    ${displayText}
                                </div>
                            </div>
                            
                            <div class="level-info-button">
                                <button id="back-to-main-comments" class="button-link" style="max-width: 200px; margin: 0 auto;">
                                    <span>Вернуться к комментариям</span>
                                    <span class="link-icon">←</span>
                                </button>
                            </div>
                        </section>
                    </div>
                `;
                
              
                mainContent.innerHTML = commentContentHTML;
                
             
                document.getElementById('back-to-main-comments').addEventListener('click', function() {
                    location.reload();
                });
                
            
                document.title = (comment.title || 'Комментарий') + ' - iipd';
                
              
                window.scrollTo(0, 0);
                document.documentElement.scrollTop = 0;
                document.body.scrollTop = 0;
            }

            
            const preventTextSelection = function(e) {
                e.preventDefault();
                return false;
            };
            
           
            const menu = {
                open: function() {
                    dom.navList.classList.add('active');
                    dom.menuOverlay.classList.add('active');
                    dom.body.style.overflow = 'hidden';
                    dom.menuToggle.innerHTML = '<i class="fas fa-times"></i><span>Закрыть</span>';
                    isMenuOpen = true;
                    dom.menuToggle.setAttribute('aria-expanded', 'true');
                },
                
                close: function() {
                    dom.navList.classList.remove('active');
                    dom.menuOverlay.classList.remove('active');
                    dom.body.style.overflow = '';
                    dom.menuToggle.innerHTML = '<i class="fas fa-bars"></i><span>Меню</span>';
                    isMenuOpen = false;
                    this.closeAllDropdowns();
                    dom.menuToggle.setAttribute('aria-expanded', 'false');
                },
                
                toggle: function() {
                    if (isMenuOpen) {
                        this.close();
                    } else {
                        this.open();
                    }
                },
                
                closeAllDropdowns: function() {
                    dom.dropdowns.forEach(dropdown => {
                        dropdown.classList.remove('active');
                        const toggle = dropdown.querySelector('.dropdown-toggle');
                        if (toggle) {
                            toggle.setAttribute('aria-expanded', 'false');
                        }
                    });
                },
                
                toggleDropdown: function(dropdown) {
                    const isActive = dropdown.classList.contains('active');
                    
                    this.closeAllDropdowns();
                    
                    if (!isActive) {
                        dropdown.classList.add('active');
                        const toggle = dropdown.querySelector('.dropdown-toggle');
                        if (toggle) {
                            toggle.setAttribute('aria-expanded', 'true');
                        }
                    }
                }
            };
            
           
            const initEventListeners = function() {
              
                dom.menuToggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    menu.toggle();
                });
                
               
                dom.themeToggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    
                    const newTheme = isLightTheme ? 'dark' : 'light';
                    applyTheme(newTheme);
                    localStorage.setItem('theme', newTheme);
                    
                  
                    gtag('event', 'theme_toggle', {
                        'event_category': 'ui_interaction',
                        'event_label': newTheme === 'light' ? 'Light Theme' : 'Dark Theme'
                    });
                });
                
             
                dom.dropdownToggles.forEach(toggle => {
                    toggle.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        const dropdown = this.parentElement;
                        menu.toggleDropdown(dropdown);
                    });
                });
                
            
                dom.menuOverlay.addEventListener('click', function() {
                    menu.close();
                });
                
             
                document.querySelectorAll('.nav-link:not(.dropdown-toggle)').forEach(link => {
                    link.addEventListener('click', function() {
                        menu.close();
                    });
                });
                
          
                document.addEventListener('click', function(e) {
                    if (isMenuOpen && 
                        !e.target.closest('.nav-list') && 
                        !e.target.closest('.menu-toggle')) {
                        menu.close();
                    }
                });
                
             
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && isMenuOpen) {
                        menu.close();
                    }
                });
                
           
                const links = document.querySelectorAll('a');
                links.forEach(link => {
                    link.addEventListener('click', function(e) {
                        if (this.dataset.clicked) {
                            e.preventDefault();
                            return;
                        }
                        
                        this.dataset.clicked = 'true';
                        setTimeout(() => {
                            delete this.dataset.clicked;
                        }, 1000);
                    });
                });
                
             
                const interactiveElements = document.querySelectorAll(
                    'button, .menu-toggle, .dropdown-toggle, .button-link, .nav-link, .dropdown-item, .telegram-icon, .theme-toggle'
                );
                
                interactiveElements.forEach(el => {
                    el.addEventListener('contextmenu', preventTextSelection);
                    el.addEventListener('selectstart', preventTextSelection);
                    el.addEventListener('mousedown', preventTextSelection);
                    
                    el.addEventListener('touchstart', function(e) {
                        if (e.touches.length > 1) {
                            e.preventDefault();
                        }
                    }, { passive: false });
                });
                
               
                interactiveElements.forEach(el => {
                    el.addEventListener('touchstart', function() {
                        this.classList.add('active-touch');
                    });
                    
                    el.addEventListener('touchend', function() {
                        this.classList.remove('active-touch');
                    });
                });
                
             
                document.querySelectorAll('.button-link').forEach(button => {
                    button.addEventListener('touchstart', function() {
                        this.style.backgroundColor = 'rgba(255, 255, 255, 0.15)';
                    });
                    
                    button.addEventListener('touchend', function() {
                        this.style.backgroundColor = '';
                    });
                });
            };
            
           
            const init = function() {
               
                initEventListeners();
                
               
                dom.menuToggle.setAttribute('aria-expanded', 'false');
                dom.menuToggle.setAttribute('aria-label', 'Открыть меню навигации');
                dom.themeToggle.setAttribute('aria-label', 'Переключить тему');
                
                
                dom.dropdownToggles.forEach(toggle => {
                    toggle.setAttribute('aria-expanded', 'false');
                    toggle.setAttribute('aria-haspopup', 'true');
                });
                
                
                const currentYear = new Date().getFullYear();
                const yearElement = document.querySelector('.nav-footer-year');
                if (yearElement) {
                    yearElement.textContent = currentYear;
                }
                
               
              
                
                
                loadLoadLevels();
                
                
                loadBlocks();
                
                
                loadComments(0);
                
                
                
                dom.navList.addEventListener('wheel', function(e) {
                    this.scrollTop += e.deltaY * 0.5;
                    e.preventDefault();
                }, { passive: false });
                
                
                let startY = 0;
                let scrollTop = 0;
                let isScrolling = false;
                
                dom.navList.addEventListener('touchstart', function(e) {
                    if (e.touches.length === 1) {
                        startY = e.touches[0].pageY;
                        scrollTop = this.scrollTop;
                        isScrolling = true;
                    }
                });
                
                dom.navList.addEventListener('touchmove', function(e) {
                    if (!isScrolling || e.touches.length !== 1) return;
                    
                    e.preventDefault();
                    const y = e.touches[0].pageY;
                    const walk = (y - startY) * 2;
                    this.scrollTop = scrollTop - walk;
                });
                
                dom.navList.addEventListener('touchend', function() {
                    isScrolling = false;
                });
                
               
                function initButtonTracking() {
                   
                    document.querySelectorAll('.button-link').forEach(button => {
                        button.addEventListener('click', function(e) {
                            const buttonText = this.querySelector('span:first-child')?.textContent || this.textContent;
                            const buttonUrl = this.href || 'no-url';
                            
                            gtag('event', 'button_click', {
                                'event_category': 'engagement',
                                'event_label': buttonText.trim(),
                                'link_url': buttonUrl
                            });
                        });
                    });
                    
                    
                    document.querySelectorAll('.nav-link:not(.dropdown-toggle)').forEach(link => {
                        link.addEventListener('click', function(e) {
                            const linkText = this.querySelector('.link-text')?.textContent || this.textContent;
                            
                            gtag('event', 'navigation_click', {
                                'event_category': 'navigation',
                                'event_label': linkText.trim(),
                                'link_url': this.href || 'no-url'
                            });
                        });
                    });
                    
                   
                    document.querySelectorAll('.dropdown-item').forEach(link => {
                        link.addEventListener('click', function(e) {
                            const linkText = this.querySelector('.dropdown-item-text')?.textContent || this.textContent;
                            
                            gtag('event', 'dropdown_click', {
                                'event_category': 'navigation',
                                'event_label': linkText.trim(),
                                'link_url': this.href || 'no-url'
                            });
                        });
                    });
                    
                 
                    const telegramIcon = document.querySelector('.telegram-icon');
                    if (telegramIcon) {
                        telegramIcon.addEventListener('click', function(e) {
                            gtag('event', 'telegram_click', {
                                'event_category': 'social',
                                'event_label': 'Telegram Icon'
                            });
                        });
                    }
                    
                  
                    const logo = document.getElementById('logo');
                    if (logo) {
                        logo.addEventListener('click', function(e) {
                            gtag('event', 'logo_click', {
                                'event_category': 'navigation',
                                'event_label': 'Logo Home'
                            });
                        });
                    }
                    
                
                    const menuToggle = document.getElementById('menu-toggle');
                    if (menuToggle) {
                        menuToggle.addEventListener('click', function(e) {
                            const menuState = document.getElementById('nav-list').classList.contains('active') ? 'closed' : 'opened';
                            
                            gtag('event', 'menu_toggle', {
                                'event_category': 'ui_interaction',
                                'event_label': `Menu ${menuState}`
                            });
                        });
                    }
                }
                
                initButtonTracking();
            }
            
          
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', init);
            } else {
                init();
            }
            
        })();
        
function loadLoadLevels() {
    fetch('load-levels.json?v=' + Date.now())
        .then(response => response.json())
        .then(data => {
            const currentLevel = data.current;
            const levelData = data.levels[currentLevel];
            
            const section = document.getElementById('load-level');
            if (!section) return;
            
            
            section.classList.remove('loading');
            
            
            section.className = 'load-level-section ' + currentLevel + '-level';
            
            const currentLevelSpan = document.getElementById('current-level-text');
            if (currentLevelSpan) currentLevelSpan.textContent = levelData.level_text;
            
            const currentLevelIcon = document.querySelector('.current-level i');
            if (currentLevelIcon) currentLevelIcon.className = 'fas ' + levelData.current_level_icon;
            
            const descTitle = document.getElementById('level-description-title');
            if (descTitle) descTitle.textContent = levelData.title;
            
            const descText = document.getElementById('level-description-text');
            if (descText) descText.innerHTML = `<strong>${levelData.description}</strong> ${levelData.details}`;
            
            const indicator = document.getElementById('level-indicator');
            if (indicator) indicator.style.left = levelData.indicator_position + '%';
            
            const indicatorIcon = indicator?.querySelector('.indicator-icon i');
            if (indicatorIcon) indicatorIcon.className = 'fas ' + levelData.icon;
        })
        .catch(error => console.error('Ошибка загрузки уровней:', error));
}
        
    </script>
    
    <script src="blocks-loader.js"></script>
</body>
</html>
