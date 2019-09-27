import * as func from './galleryGetBlobsFunc.js';

$(document).ready(() =>
{

  fetchSelectedPage(1);
  getSearchedImages('#btnsearchfortags');
  confirmPageOpen('#fetchall');
  selectOrDeselectAllImagesInPage('#selectall',true);
  selectOrDeselectAllImagesInPage('#deselectall',false);
  deleteSelectedImages('#deleteselected');
  getShareableLink('#getlink');

})

function fetchSelectedPage(index)
{
    fetch('/../php/GetJsonBlobs.php?indexpage='+(index-1)).then((result) => (result.json())).then((data) => {
    func.displayImagesForSubPage(data.pageData.blobs, data.pageData.tempToken);
    if (!(window.location.pathname === '/getallblobs.php')){
      startPaginationCreation(data.pageData, index-1);
    }
    enableDeleteOneIfNoneAreSelected();
    enableDeleteForSingleImage(data.pageData, index-1);
  })
};

function startPaginationCreation(dataJson, indexPage)
{
  let pagesToRender = Math.ceil(dataJson.totalBlobsCount/dataJson.maxBlobsPerSubPage);
  func.createPagination(pagesToRender, indexPage);

  $('.fetchSelectedPage').on('click', (event) => {
    fetchSelectedPage($(event.target).attr('value'));
  })
  $('.fetchPagination').on('click', (event) => {
    startPaginationCreation(dataJson, $(event.target).attr('value')-1);
  })
}

function getSearchedImages(id, indexPage)
{
  $(id).on('click', (event) => {
    let body = new FormData();
    let tagsInserted = getWordsSplittedBySpace('#taginput');
    if (tagsInserted[0] == ""){
      $('#sharelink').val('');
      fetchSelectedPage(1);
      return;
    }
    let tags = JSON.stringify(tagsInserted);
    body.append('tags', tags);
    body.append('indexpage', indexPage = 0);
    var myInit = { method: 'POST',
                body: body,
                };
    fetchSearchedPage(1, myInit);
  })
}

function getWordsSplittedBySpace(id)
{
  let tags = $(id).val().trim();
  return tags.split(" ");
}

function fetchSearchedPage(index, postPar)
{
  fetch('/../php/GetSearchedByTagBlobs.php', postPar).then((result) => (result.text())).then((data) => {
    let json;
    try{
      json = JSON.parse(data);
    } catch (err) {
      console.log('Nessun risultato per la ricerca effettuata.');
      return;
    }
    func.displayImagesForSubPage(json.pageData.blobs, json.pageData.tempToken);
    if (!(window.location.pathname === '/getallblobs.php')){
      startPaginationCreationForSearch(json.pageData, index-1);
    }
    enableDeleteOneIfNoneAreSelected();
    enableDeleteForSingleImage(json.pageData, index-1);
  })
};

function startPaginationCreationForSearch(dataJson, indexPage)
{
  let pagesToRender = Math.ceil(dataJson.totalBlobsCount/dataJson.maxBlobsPerSubPage);
  func.createPagination(pagesToRender, indexPage);

  $('.fetchSelectedPage').on('click', (event) => {
    getSearchedImages('#btnsearchfortags', $(event.target).attr('value'));
  })
  $('.fetchPagination').on('click', (event) => {
    startPaginationCreationForSearch(dataJson, $(event.target).attr('value')-1);
  })
}

function confirmPageOpen(id)
{
  $(id).on('click', (event)=>{
    if (!confirm("L'apertura di questa pagina mostra tutte le immagini che hai salvato in cloud."+
    "A seconda del numero presente, può portare a una pagina molto pesante o richiedere un alto consumo di banda."+
    "Sei sicuro di volerla aprirla?")) {
      event.preventDefault();
    }
  })
}

function enableDeleteOneIfNoneAreSelected()
{
  $('.blankCheckbox').on('click', (event) => {
    $('.divForImagesShowing input:checked').length>0 ? $('.btnDeleteOne').prop('disabled', true) : $('.btnDeleteOne').removeAttr('disabled');
  })
}

function enableDeleteForSingleImage(dataJson, indexPage)
{
  $('.btnDeleteOne').on('click', (event) => {
    var myInit = { method: 'DELETE'};
    fetch('/../php/DeleteBlobs.php?name='+$(event.target).val(), myInit).then((result) => (result.text())).then((data) => {
      if (data!= 'Delete successful'){
        alert("Delete not successful.");
        return;
      }
      fetchSelectedPage(indexPage+1);
    })
  })
}

function selectOrDeselectAllImagesInPage(id, boolValue)
{
  $(id).on('click', (event) => {
    $('.blankCheckbox').prop('checked', boolValue);
    id == '#selectall' ? $('.btnDeleteOne').prop('disabled', true) : $('.btnDeleteOne').removeAttr('disabled');
  })
}

function deleteSelectedImages(id)
{
  $(id).on('click', (event) => {
    let parameterImages = func.createQueryStringFromImagesValue('.divForImagesShowing input:checked');
    if (parameterImages === ''){
      return;
    }
    var myInit = { method: 'DELETE'};
    fetch('/../php/DeleteBlobs.php?'+parameterImages, myInit).then((result) => (result.text())).then((data) => {
      if (data!= 'Delete successful'){
        alert("Some deletes were not successful. Please check after page reload.");
      }
      fetchSelectedPage($('.pagination li.active').children('a').attr('value'));
    })
  })
}

function getShareableLink(id)
{
  prepareShareUsage();
  $(id).on('click', (event) => {
    let body = new FormData();
    let expirydate = validateDate();
    let parameterImages = func.getValueOfSelectedImages('.divForImagesShowing input:checked');

    if (parameterImages == '' || expirydate == false ){
      return;
    }

    $('[data-toggle="popover"]').popover('hide');
    let imgs = JSON.stringify(parameterImages);
    body.append('expirydate', expirydate);
    body.append('imgname', imgs);
    var myInit = { method: 'POST',
                body: body,
                };
    fetch('/../php/CreateShareableLink.php', myInit).then((result) => (result.text())).then((data) => {
      $('#sharelink').val(data);
    })
  })
}

function prepareShareUsage()
{
  let d = new Date();
  let d2 = new Date();
  d.setHours(d.getHours()+2);
  d2.setMonth(d.getMonth()+6);
  $('#datetimepicker').attr('min', d.getFullYear() + '-0' + (d.getMonth()+1) + '-' + d.getDate());
  $('#datetimepicker').attr('max', d2.getFullYear() + '-0' + (d2.getMonth()+1) + '-' + d2.getDate());
  $('[data-toggle="popover"]').popover()
  let clipboard = new ClipboardJS('.copy');
}

function validateDate()
{
  let date = $('#datetimepicker').val();
  let hour = $('#hourpicker').val();
  let actualDate = new Date();
  actualDate.setHours(actualDate.getHours()+2);
  let choosenDate = new Date(date + 'T' + hour +'Z');
  if (date == '' || hour == '' || actualDate>choosenDate){
    return false;
  }
  choosenDate.setHours(choosenDate.getHours()-2);
  return choosenDate.getTime();
}
