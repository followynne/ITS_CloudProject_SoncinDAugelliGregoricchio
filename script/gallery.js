console.log("ciao bella, testing");

$(document).ready(() => {
  fetchSelectedPage(1);
})

function fetchSelectedPage(index){
  fetch('/../php/GetJsonBlobs.php?indexpage='+(index-1)).then((result) => (result.json())).then((data) => {
    createSubPage(data.pageData, index-1);
    startPaginationCreation(data.pageData, index-1);
  })
};

function createSubPage(dataJson, indexPage){
  //displayImagesForSubPage(dataJson.blobs);
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

function displayImagesForSubPage(blobs){
  $.each(blobs, function(key, value) {
    $(".divForImagesShowing").html(
      '<div class="col-lg-6 col-sm-6">' +
      '<div class="card" style="width: 18rem;"> ' +
      '<a href="'+ value.url +'"><img src="' + value.url +
      '"/></a></div></div></div>');
  });
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
    paginationRender += addPage(pagesToCycleThrough, 1);
  } else
  {
    if (indexSelectedPage >= 0 && indexSelectedPage <= 3){
      paginationRender += addPage(pagesToCycleThrough, 1);
      paginationRender += addProgressiveElement(1 + pagesButtonMaxAmount) + addToEndElement(totalPages);
    } else if (indexSelectedPage < indexToStartFromForCorrectDisplayFromEndLine) {
      paginationRender += addPage(indexSelectedPage+pagesToCycleThrough, indexSelectedPage);
      let tmp = addToEndElement(1) + addProgressiveElement(indexSelectedPage-1);
      paginationRender = tmp + paginationRender + addProgressiveElement(indexSelectedPage+pagesButtonMaxAmount+1) + addToEndElement(totalPages);
    } else {
      paginationRender += addToEndElement(1) + addProgressiveElement(indexToStartFromForCorrectDisplayFromEndLine);
      paginationRender += addPage(totalPages, indexToStartFromForCorrectDisplayFromEndLine+1);
    }
  }
  $(".pagination").html(paginationRender);
}

function addPage(lastIndexPageOfCycle, startingIndexPage){
  let htmlVar = "";
  for (var i=startingIndexPage; i<=lastIndexPageOfCycle;i++){
    htmlVar += '<li class="page-item fetchSelectedPage"><a class="page-link" value="'+i+'">'+i+'</a></li>';
  }
  return htmlVar;
}

function addProgressiveElement(progressiveElement){
  let htmlVar = '<li class="page-item fetchPagination"><a class="page-link" value="'+progressiveElement+'" onclick="">...</a></li>';
  return htmlVar;
}

function addToEndElement(finalPage){
  let htmlVar = '<li class="page-item fetchSelectedPage"><a class="page-link" value="'+finalPage+'" onclick="">'+finalPage+'</a></li>';
  return htmlVar;
}
