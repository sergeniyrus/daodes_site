show.visible = 'part1img';
show.hidden = 'part1';

show.visible1 = 'read';
show.hidden1 = 'noread';

function show(){
show.hidden = show.visible;
show.visible = (show.visible === 'part1img')?'part1':'part1img';

document.getElementById(show.visible).style.display = 'block';
document.getElementById(show.hidden).style.display = 'none';

show.hidden1 = show.visible1;
show.visible1 = (show.visible1 === 'read')?'noread':'read';

document.getElementById(show.visible1).style.display = 'block';
document.getElementById(show.hidden1).style.display = 'none';

}



// show.visible2 = 'read-block';
// show.hidden2 = 'winvid';

// show.visible3 = 'vid';
// show.hidden3 = 'novid';

// function vid(){
//     show.hidden2 = show.visible2;
//     show.visible2 = (show.visible2 === 'read-block')?'winvid':'read-block';
    
//     document.getElementById(show.visible2).style.display = 'block';
//     document.getElementById(show.hidden2).style.display = 'none';    
    
//     show.hidden3 = show.visible3;
//     show.visible3 = (show.visible3 === 'vid')?'novid':'vid';
    
//     document.getElementById(show.visible3).style.display = 'block';
//     document.getElementById(show.hidden3).style.display = 'none';
// }