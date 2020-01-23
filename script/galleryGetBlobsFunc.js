export function displayImagesForSubPage(blobs, tempToken){
  let images = '';
  $.each(blobs, function(key, value) {
    images += '<div class="col-3 p-3"> ' +
    '​<picture>'+
    '<div class="card-img-top">'+
    '<div class="embed-responsive embed-responsive-4by3">'+
    '<div class="embed-responsive-item">'+
    '<a href="/public/showsingleblob.php?name='+ value.name +'" target="_blank">'+
    '<img class="img-fluid w-100" src="' + value.url + '?' + tempToken + '"/>'+
    '</a></div></div></div></div></​picture>'+
    '<div><input class="form-check-input position-static mt-4 blankCheckbox" type="checkbox"'+
    '" style="margin:auto;display:block">'+
    '<button type="button" class="btn btn-danger mt-2 btnDeleteOne" value="'+ value.name +'" style="height:35px;">Delete</button></div>';
  });
  $(".divForImagesShowing").html(images);
}

/**
 * It takes the total n of pages to be rendered (related to blobs) and the selected index page by user. 
 * @var {*} pagesButtonMaxAmount => how many buttonNumber to display before showing the progressive element  
 * @var {*} pagesToCycleThrough => given the total pages to render, is it more or less than the Max amount set?
 *                                 [pagesButtonMaxAmount+1 helps to cover the edge case] 
 * @var {*} areProgressiveElementNeeded => bool, helps to understand if progressive element are needed
 * @var {*} indexToStartFromForCorrectDisplayFromEndLine => index to start from when adding pagination element from the end
 *                                                          (ex. 1-prog.el.-numbers) 
 * @If 1 <= pages to be rendered <= 7  @Then progressive element isn't required. Cycles from 1 to pagesToCycleThrough
 * @Not : a. @If 0 <= indexSelectedPage <= 3 @Then pagination goes from button 1 to pagesButtonMaxAmount, then renders
 *           the progressive el. button, then the finalPage button}
 *        b. @If indexToStartFrom[...] 0||1 @Then  pagination goes from button 1 to pagesButtonMaxAmount, then renders 
 *           the progressive el.button fixed at 4, the the buttons pages from 5 to totalPages.
 *           @Note This escamotage was used to solve the issue with totalPages edge cases ex: 7,8.
 *           When TotalPages were 7/8, it was created a progressive el button and a final button with the same value.
 *           ex: [|1|2|3|4|5|6|(...)|7|] -> [Click on (...) created |7|8|9|... while not existing] 
 *           To solve the issue, it was created an exception case to intercept those elements. 
 *        c. @If indexSelectedPage<indexToStartFrom... @Then pagination spawns StartButton(1), progressive element (going backwards), 
 *           central numbers buttons, progressive element (going onwards), FinalButton(lastpagenumber)
 *        d. @If none of previous @Then pagination goes StartButton(1), progressive element (going backwards), indexSelected to LastPage buttons.
 */
export function createPagination(totalPages, indexSelectedPage){
  let paginationRender = "";
  const pagesButtonMaxAmount = 6; // please select from 3+ onwards. 
  let pagesToCycleThrough = totalPages <= pagesButtonMaxAmount+1 ? totalPages : pagesButtonMaxAmount;
  let areProgressiveElementNeeded = totalPages > pagesButtonMaxAmount+1;
  let indexToStartFromForCorrectDisplayFromEndLine = totalPages-pagesButtonMaxAmount-2;
  
  if (!areProgressiveElementNeeded)
  {
    paginationRender += addPage(pagesToCycleThrough, 1, indexSelectedPage);
  }
  else
  {
    if (indexSelectedPage >= 0 && indexSelectedPage <= 3){
      paginationRender += addPage(pagesToCycleThrough, 1, indexSelectedPage);
      paginationRender += addProgressiveElement(1 + pagesButtonMaxAmount) + addToEndElement(totalPages);
    }
    else if (indexToStartFromForCorrectDisplayFromEndLine == 0 || indexToStartFromForCorrectDisplayFromEndLine == 1){
      paginationRender += addToEndElement(1) + addProgressiveElement(4);
      paginationRender += addPage(totalPages, 5, indexSelectedPage);
    }
    else if (indexSelectedPage < indexToStartFromForCorrectDisplayFromEndLine) {
      paginationRender += addPage(indexSelectedPage+pagesToCycleThrough, indexSelectedPage, indexSelectedPage);
      let tmp = addToEndElement(1) + addProgressiveElement(indexSelectedPage-1);
      paginationRender = tmp + paginationRender + addProgressiveElement(indexSelectedPage+pagesButtonMaxAmount+1) + addToEndElement(totalPages);
    }
    else {
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

export function createQueryStringFromImagesValue(divToCheck){
  let parameterImages = '';
  let values = getValueOfSelectedImages(divToCheck);
  $.each(values, function(index, value) {
    parameterImages+= 'name[]='+value+'&';
  })
  return parameterImages = parameterImages.substr(0, parameterImages.length-1);
}

export function getValueOfSelectedImages(divToCheck){
  let values = [];
  $(divToCheck).siblings().each(function() {
    values.push($(this).val());
  })
  return values;
}
