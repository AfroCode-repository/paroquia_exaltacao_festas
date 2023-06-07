{{--script em Js, para visualziar melhor basta apenas colocar a expressão entre as taks <script>...</script> --}}

    let alertPreencher = () => {
        Swal.fire('Atenção!','Preencha os campos em vermelho!','warning')
    }

    let hasClass = (el, className) => {
        if (el.classList)
            return el.classList.contains(className)
        else
            return !!el.className.match(new RegExp('(\\s|^)' + className + '(\\s|$)'))
    }

    let addClass = (el, className) => {
        if (el.classList)
            el.classList.add(className)
        else if (!hasClass(el, className)) el.className += " " + className
    }

    let removeClass = (el, className) => {
        if (el.classList)
            el.classList.remove(className)
        else if (hasClass(el, className)) {
            var reg = new RegExp('(\\s|^)' + className + '(\\s|$)')
        el.className=el.className.replace(reg, ' ')
        }
    }

    let removeAndAddClass = (el, className) => {
        removeClass(el, className)
        addClass(el, className)
    }
