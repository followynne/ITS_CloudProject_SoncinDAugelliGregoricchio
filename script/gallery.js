$(document).ready(() => {
  fetchSelectedPage(1);
})

function fetchSelectedPage(index){
    fetch('/../php/GetJsonBlobs.php?indexpage='+(index-1)).then((result) => (result.json())).then((data) => {
    displayImagesForSubPage(data.pageData.blobs);
    startPaginationCreation(data.pageData, index-1);
  })
};

function displayImagesForSubPage(blobs){
  let images = '';
  $.each(blobs, function(key, value) {
    images += '<div class="col-3 p-3"> ' +
    '<div class="card h-100 border-dark">'+
    '<div class="card-img-top">'+
    '<div class="embed-responsive embed-responsive-4by3">'+
    '<div class="embed-responsive-item">'+
    '<a href="'+ value.url +'">'+
    '<img class="img-fluid w-100" src="' + value.url + '"/>'+
    '</a></div></div></div></div></div>';
  });
  $(".divForImagesShowing").html(images);
}

function startPaginationCreation(dataJson, indexPage){
  let pagesToRender = Math.ceil(dataJson.totalBlobsCount/dataJson.maxBlobsPerSubPage);
  createPagination(pagesToRender, indexPage);

  $('.fetchSelectedPage').on('click', (event) => {
    fetchSelectedPage($(event.target).attr('value'));
  })
  $('.fetchPagination').on('click', (event) => {
    startPaginationCreation(dataJson, $(event.target).attr('value')-1);
        })
}

function createPagination(totalPages, indexSelectedPage){
  let paginationRender = "";
  const pagesButtonMaxAmount = 6;
  let pagesToCycleThrough = 0;
  pagesToCycleThrough = totalPages <= pagesButtonMaxAmount ? totalPages : pagesButtonMaxAmount;
  let areProgressiveElementNeeded = totalPages >= pagesButtonMaxAmount;
  let indexToStartFromForCorrectDisplayFromEndLine = totalPages-pagesButtonMaxAmount-2;

  if (!areProgressiveElementNeeded)
  {
    paginationRender += addPage(pagesToCycleThrough, 1, indexSelectedPage);
  } else
  {
    if (indexSelectedPage >= 0 && indexSelectedPage <= 3){
      paginationRender += addPage(pagesToCycleThrough, 1, indexSelectedPage);
      paginationRender += addProgressiveElement(1 + pagesButtonMaxAmount) + addToEndElement(totalPages);
    } else if (indexSelectedPage < indexToStartFromForCorrectDisplayFromEndLine) {
      paginationRender += addPage(indexSelectedPage+pagesToCycleThrough, indexSelectedPage, indexSelectedPage);
      let tmp = addToEndElement(1) + addProgressiveElement(indexSelectedPage-1);
      paginationRender = tmp + paginationRender + addProgressiveElement(indexSelectedPage+pagesButtonMaxAmount+1) + addToEndElement(totalPages);
    } else {
      paginationRender += addToEndElement(1) + addProgressiveElement(indexToStartFromForCorrectDisplayFromEndLine);
      paginationRender += addPage(totalPages, indexToStartFromForCorrectDisplayFromEndLine+1, indexSelectedPage);
    }
  }
  $(".pagination").html(paginationRender);
}

function addPage(lastIndexPageOfCycle, startingIndexPage, indexSelectedPage){
  let htmlVar = "";
  for (var i=startingIndexPage; i<=lastIndexPageOfCycle;i++){
    if (i-1==indexSelectedPage) {
      htmlVar += '<li class="page-item fetchSelectedPage page-item active"><a href="#" class="page-link" value="'+i+'">'+i+'</a></li>';
    } else {
      htmlVar += '<li class="page-item fetchSelectedPage"><a href="#" class="page-link" value="'+i+'">'+i+'</a></li>';
    }
  }
  return htmlVar;
}

function addProgressiveElement(progressiveElement){
  let htmlVar = '<li class="page-item fetchPagination"><a  href="#" class="page-link" value="'+progressiveElement+'" onclick="">...</a></li>';
  return htmlVar;
}

function addToEndElement(finalPage){
  let htmlVar = '<li class="page-item fetchSelectedPage"><a  href="#" class="page-link" value="'+finalPage+'" onclick="">'+finalPage+'</a></li>';
  return htmlVar;
}
