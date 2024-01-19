const colors = require("tailwindcss/colors");
const defaultTheme = require("tailwindcss/defaultTheme");

module.exports = {
    content: [
        "./vendor/wire-elements/modal/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/css/**/*.css",
    ],
    safelist: [
        'sm:max-w-sm',
        'sm:max-w-md',
        'sm:max-w-md md:max-w-lg',
        'sm:max-w-md md:max-w-xl',
        'sm:max-w-md md:max-w-xl lg:max-w-2xl',
        'sm:max-w-md md:max-w-xl lg:max-w-3xl',
        'sm:max-w-md md:max-w-xl lg:max-w-3xl xl:max-w-4xl',
        'sm:max-w-md md:max-w-xl lg:max-w-3xl xl:max-w-5xl',
        'sm:max-w-md md:max-w-xl lg:max-w-3xl xl:max-w-5xl 2xl:max-w-6xl',
        'sm:max-w-md md:max-w-xl lg:max-w-3xl xl:max-w-5xl 2xl:max-w-7xl',
        'grid-cols-1 grid-cols-2 grid-cols-3 grid-cols-4 grid-cols-5 grid-cols-6 grid-cols-7 grid-cols-8 grid-cols-9 grid-cols-10 grid-cols-11 grid-cols-12',
        'bg-[#153A5B#008131] bg-[#fdd835] bg-[#FBB040] bg-[#c81e1e] bg-[#ff0000] bg-[#ffa500] bg-[#008000] bg-[#f57c00] bg-[#fdd835] bg-[#008131] bg-[#e3253e] bg-[#e3253e] bg-[#dda83a] bg-[#4c9f38] bg-[#c5192d] bg-[#ff3a21] bg-[#26bde2] bg-[#fcc30b] bg-[#a21942] bg-[#fd6925] bg-[#dd1367] bg-[#fd9d24] bg-[#bf8b2e] bg-[#3f7e44] bg-[#0a97d9] bg-[#56c02b] bg-[#00689d] bg-[#19486a] bg-[#e86321ff] bg-[#774294] bg-[#458D88]',
        'border-[#0D9401] border-[#F44336] border-[#FBC02D]',
        'w-[0%] w-[1%] w-[2%] w-[3%] w-[4%] w-[5%] w-[6%] w-[7%] w-[8%] w-[9%] w-[10%] w-[11%] w-[12%] w-[13%] w-[14%] w-[15%] w-[16%] w-[17%] w-[18%] w-[19%] w-[20%] w-[21%] w-[22%] w-[23%] w-[24%] w-[25%] w-[26%] w-[27%] w-[28%] w-[29%] w-[30%] w-[31%] w-[32%] w-[33%] w-[34%] w-[35%] w-[36%] w-[37%] w-[38%] w-[39%] w-[40%] w-[41%] w-[42%] w-[43%] w-[44%] w-[45%] w-[46%] w-[47%] w-[48%] w-[49%] w-[50%] w-[51%] w-[52%] w-[53%] w-[54%] w-[55%] w-[56%] w-[57%] w-[58%] w-[59%] w-[60%] w-[61%] w-[62%] w-[63%] w-[64%] w-[65%] w-[66%] w-[67%] w-[68%] w-[69%] w-[70%] w-[71%] w-[72%] w-[73%] w-[74%] w-[75%] w-[76%] w-[77%] w-[78%] w-[79%] w-[80%] w-[81%] w-[82%] w-[83%] w-[84%] w-[85%] w-[86%] w-[87%] w-[88%] w-[89%] w-[90%] w-[91%] w-[92%] w-[93%] w-[94%] w-[95%] w-[96%] w-[97%] w-[98%] w-[99%] w-[100%]',
        'text-[red] text-[orange] text-[yellowgreen] text-[green]', "bg-[#4C8056]", "bg-[#4c9f38]",
        'row-span-1 row-span-2 row-span-3 row-span-4 row-span-5 row-span-6 grid-rows-1 grid-rows-2 grid-rows-3 grid-rows-4 grid-rows-5 grid-rows-6',
        'pl-2', 'pl-4', 'pl-6', 'pl-8',
        '!pl-2', '!pl-4', '!pl-6', '!pl-8',
        'ring-offset-0 ring-0',
        'bg-[#FD8D3C]/20', 'bg-[#757575]', 'text-[#757575]'
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Lato', ...defaultTheme.fontFamily.sans],
                inter: ['Inter var', ...defaultTheme.fontFamily.sans],
                encodesans: ['Encode sans', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                esg0: '#fff0 !important',
                esg1: '#FBB040',
                esg2: '#99CA3C',
                esg3: '#06A5B4',
                esg4: '#FFFFFF',
                esg5: '#E86321',
                esg6: '#153A5B',
                esg7: '#C4C4C4',
                esg8: '#444444',
                esg9: 'rgba(165, 162, 162, 0.6)',
                esg10: '#DDE1E6',
                esg11: '#A2A9B0',
                esg12: '#343A3F',
                esg13: '#EA8F68',
                esg14: '#FDF8F6',
                esg15: '#F2F2F2',
                esg16: '#757575',
                esg17: 'rgba(253, 253, 253, 0.6)',
                esg18: '#DCDCDC',
                esg19: '#FBFBFB',
                esg20: '#DD644E',
                esg21: '#458D88',
                esg22: '#774294',
                esg23: '#1D476F',
                esg24: '#FBB040',
                esg25: '#99CA3C',
                esg26: '#06A5B4',
                esg27: '#FFFFFF',
                esg28: '#E86321',
                esg29: '#153A5B',
                esg30: 'rgba(153, 202, 60, 0.25)',
                esg31: 'rgba(253, 157, 36, 0.25)',
                esg32: '#EBECED',
                esg33: '#E8621F',
                esg34: '#FD9D24',
                esg35: '#8A8A8A',
                esg36: '#C5192D',
                esg37: '#F9F9F9',
                esg38: '#D9D9D9',
                esg39: '#181818',
                esg40: '#1E1E1E',
                esg41: '#F8FAFC',
                esg42: '#333333',
                esgwl: 'rgba(203, 42, 139, 1)',
                esg43: '#CCCCCC',
                esg44: 'rgba(21, 58, 91, 0.1)',
                esg45: '#F8FAFC',
                esg46: '#8A8A8A',
                esg55: '#D9D9D9',
                esg58: 'rgba(21, 58, 91, 0.13)',
                esg59: '#FAF9FB',
                esg60: '#d0d0d0',
                esg61: '#E1E6EF',
                esg62: 'rgba(21, 58, 91, 1)',
                orange: colors.orange,
                yellowgreen: colors.yellowgreen,
                esg63: "#058894",
                esg64: "#21A6E8",
                esg65: "#FF0000",
                esg66: "#f5ec42",
                esg67: "#D0D5DD",
                esg68: "#FF0000",
                transparent: "transparent",
                esg69: "#6DA979",
                esg70: "#44724D",
                esg71:'#F0F0F0',
                esg72: '#EBEEF4',
                esg73: '#AAB3CF',
                esg72:'#EBEEF4'
            },
            boxShadow: {
                bxesg1: "0px 2px 4px rgba(58, 92, 144, 0.14), 0px 3px 4px rgba(58, 92, 144, 0.12), 0px 1px 5px rgba(58, 92, 144, 0.2)",
            },
            width: {
                "1/100": "1%",
            },
            height: {
                "3/5": "60%",
            },
            screens: {
                xs: "325px",
            },
            zIndex: {
                99999: "99999",
            }
        },
    },
    plugins: [require("flowbite/plugin")],
    variants: {
        extend: {
            visibility: ["group-hover"],
        },
    },
};