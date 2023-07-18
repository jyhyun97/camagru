export function changeHiddenStatus(elements)
{
    const objectArray = Object.keys(elements).map(ele => elements[ele]);
    objectArray.forEach((ele) => {
        ele.hidden = !ele.hidden;
    });
}

export function changeHiddenStatusBootstrap(elements)
{
    const objectArray = Object.keys(elements).map(ele => elements[ele]);
    objectArray.forEach((ele) => {
        if (ele.className.includes('hidden'))
            ele.className = ele.className.replace('hidden', '');
        else
            ele.className = ele.className.concat(' hidden');
    });
}