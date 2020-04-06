import * as func from './galleryGetBlobsFunc.js';

$(document).ready(() => {
  fetchSelectedPage(1);
})

function fetchSelectedPage(index)
{
    let searchParams = new URLSearchParams(window.location.search);
    let url = searchParams.get('url');
    fetch('/sharedblobslist?url=' + url + '&indexpage='+(index-1)).then((result) => (result.json())).then((data) => {
    displayImagesForSubPage(data.pageData.blobs);
    startPaginationCreation(data.pageData, index-1);
    })
};

function displayImagesForSubPage(blobs){
  let images = '';
  $.each(blobs, function(value, key) {
    images += '<div class="col-3 p-3 "> ' +
    '<div class="card w-100">'+
    '<div class="view overlay">'+
    '<img class="card-img-top" src="' + key.url + '"/>'+
    '<a href="'+ key.url +'" target="_blank">'+
    '</a></div></div>'+
    '</div>';
  });
  $(".divForImagesShowing").html(images);
}

function startPaginationCreation(dataJson, indexPage){
  let pagesToRender = Math.ceil(dataJson.totalBlobsCount/dataJson.maxBlobsPerSubPage);
  func.createPagination(pagesToRender, indexPage);

  $('.fetchSelectedPage').on('click', (event) => {
    fetchSelectedPage($(event.target).attr('value'));
  })
  $('.fetchPagination').on('click', (event) => {
    startPaginationCreation(dataJson, $(event.target).attr('value')-1);
  })
}
