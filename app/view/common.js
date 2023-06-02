export function changeHiddenStatus(elements)
{
    const objectArray = Object.keys(elements).map(ele => elements[ele]);
    if (objectArray.length < 1)
        console.log('나중에 throw');
    
    objectArray.forEach((ele) => {
        ele.hidden = !ele.hidden;
    });
}