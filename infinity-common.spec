Name: infinity-common
Summary: Infinity Common Classes
Version: %version
Release:  1
License: Not Applicable
Group: Development/Library
URL: http://www.infinitycloud.com
BuildRoot: %{_tmppath}/%{name}-root
Source0: %{name}-%{version}.tar.gz
Requires: php >= 5.5, php-mbstring
BuildArch: noarch

%description
This package contains common PHP classes used by Infinity applications.
It installs into /var/lib/infinity-common

%prep
%setup -q

%build

%install
[ "$RPM_BUILD_ROOT" != "/" ] && rm -rf $RPM_BUILD_ROOT

export INSTALL_ROOT=$RPM_BUILD_ROOT
install -d -m 755 $RPM_BUILD_ROOT/var/lib/%{name}
cp -R * $RPM_BUILD_ROOT/var/lib/%{name}

%clean
rm -rf $RPM_BUILD_ROOT

%files
%defattr(-,root,root,-)
/var/lib/%{name}
