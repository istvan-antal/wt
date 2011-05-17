#!/bin/bash
rm -rf debian/usr/share/jsop/*
cp -r ../../* debian/usr/share/jsop
rm -rf debian/usr/share/jsop/nbproject
rm -rf debian/usr/share/jsop/pkg
dpkg-deb --build debian
mv debian.deb jsop-$1-all.deb
rm -rf debian/usr/share/jsop/*
