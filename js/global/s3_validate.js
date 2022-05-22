"use strict";

const safe_chars_regexp = /\p{C}|\p{Z}|\u034f|\u115f|\u1160|\u17b4|\u17b5|\u180e|\u2800|\u3164|\uffa0/gu;
const space_regexp = / /gu;

function validChars(subject) {
  let char_test = subject.match(safe_chars_regexp);
  if(char_test !== null) {
    let space_test = subject.match(space_regexp);
    if(
        space_test === null 
        || char_test.length > space_test.length
        || space_test.length === subject.length
      )
      return false;
  }
  return true;
}

function byteLength(subject) {
  let byte_len = 0;

  for(let i = 0; i < subject.length; i++) {
    const code = subject.charCodeAt(i);
    if(code <= 0xff)byte_len += 1;
    else if(code <= 0xffff)byte_len += 2;
    else if(code <= 0xffffff)byte_len += 3;
    else byte_len += 4;
  }
  return byte_len;
}

function validByteLength(subject, min = 0, max = 2048) {
  const len = byteLength(subject);
  if(len < min || len > max)
    return false;
  return true;
}

function validEmail(subject) {
  if(
      !subject.includes('@')
      || !subject.includes('.')
      || subject.search(/[^A-Za-z0-9!#$%&'*+-/=?^_`{|}~.@" (),:;<>[]]/) !== -1
    )
    return false;

  const at_pos = subject.lastIndexOf('@');
  const local_part = subject.slice(0, at_pos);
  const domain_part = subject.slice(at_pos + 1);

  if(      
      !validByteLength(local_part, 1, 64)
      || !validByteLength(domain_part, 4, 189)
    )
    return false;
    
  return true;
}

function validLogin(subject) {
  if(
      !validByteLength(subject, 3, 32)
      || !validChars(subject)
    )
    return false;
  return true;
}

function validPassword(subject) {
  if(!validByteLength(subject, 5, 72))
    return false;
  return true;
}
